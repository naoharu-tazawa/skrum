import React, { Component } from 'react';
import * as d3 from 'd3';
import _ from 'lodash';
import { d3treePropTypes } from './propTypes';

export default class D3Tree extends Component {

  static propTypes = {
    map: d3treePropTypes.isRequired,
  };

  static root;

  static isHidden = false;

  componentDidMount() {
    // Render the tree usng d3 after first component mount
    this.renderTree(this.props.map, this.node);
  }

  shouldComponentUpdate() { // nextProps, nextState
    // Delegate rendering the tree to a d3 function on prop change
    // this.renderTree(this.props.map, this.getDOMNode());

    // Do not allow react to render the component on prop change
    return false;
  }

  splitByLength(str, length) {
    const resultArr = [];
    if (!str || !length || length < 1) {
      return resultArr;
    }
    let index = 0;
    let start = index;
    let end = start + length;
    while (start < str.length) {
      resultArr[index] = str.substring(start, end);
      index += 1;
      start = end;
      end = start + length;
      if (index === 3) { break; }
    }
    return resultArr;
  }

  // 改行のためtspanを作成
  leftLinebreak(text) {
    let string = '';
    const array = this.splitByLength(text, 14);
    array.forEach((t, i) => {
      if (i === 2 && t.length === 14) {
        string += `<tspan class="line${i}" y="${i - 9.5}em" x="-6.9em">${t.substr(0, t.length - 1)}…</tspan>`;
      } else {
        string += `<tspan class="line${i}" y="${i - 9.5}em" x="-6.9em">${t}</tspan>`;
      }
    });
    return string;
  }

  // ドロップシャドウ
  dropShadow(nodeEnter) {
    const defs = nodeEnter.append('defs');

    const filter = defs.append('filter')
      .attr('id', 'drop-shadow')
      .attr('height', '130%');

    filter.append('feGaussianBlur')
      .attr('in', 'SourceAlpha')
      .attr('stdDeviation', 1)
      .attr('result', 'blur');

    filter.append('feOffset')
      .attr('in', 'blur')
      .attr('dx', 1)
      .attr('dy', 1)
      .attr('result', 'offsetBlur');

    const feMerge = filter.append('feMerge');
    feMerge.append('feMergeNode')
      .attr('in', 'offsetBlur');
    feMerge.append('feMergeNode')
      .attr('in', 'SourceGraphic');
  }

  // プログレスバー生成
  createProgressBar(nodeEnter, reductionRatio) {
    // 再描画時に直前のプログレスバーを消す
    nodeEnter.selectAll('rect.bg-rect').remove();
    nodeEnter.selectAll('rect.progress-rect').remove();

    const states = ['started', 'inProgress', 'completed'];
    const segmentWidth = 134 * reductionRatio;
    const startedCap = 30;
    const inProgressCap = 70;
    let currentState;

    const colorScale = d3.scaleOrdinal()
      .domain(states)
      .range(['#EFB04C', '#AFDB56', '#42BBF8']);

    nodeEnter.append('rect')
      .attr('class', 'bg-rect')
      .attr('rx', 0)
      .attr('ry', 0)
      .attr('fill', '#D8DFE5')
      .attr('height', 15 * reductionRatio)
      .attr('width', segmentWidth)
      .attr('y', `${-77 * reductionRatio}px`)
      .attr('x', `${-45 * reductionRatio}px`)
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    // nodeEnter.append('rect')
    const progress = nodeEnter.append('rect')
      .attr('class', 'progress-rect')
      .attr('fill', (d) => {
        if (d.data.achievementRate < startedCap) {
          currentState = 'started';
        } else if (d.data.achievementRate < inProgressCap) {
          currentState = 'inProgress';
        } else {
          currentState = 'completed';
        }
        return colorScale(currentState);
      })
      .attr('height', 15 * reductionRatio)
      .attr('width', 0)
      .attr('rx', 0)
      .attr('ry', 0)
      .attr('y', `${-77 * reductionRatio}px`)
      .attr('x', `${-45 * reductionRatio}px`)
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    progress.transition()
      .duration(1000)
      .attr('width', (d) => {
        if (d.data.achievementRate >= 100) {
          return segmentWidth;
        }
        return (d.data.achievementRate / 100) * segmentWidth;
      });
  }

  // Collapse the node and all it's children
  collapse(self, d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(_.partial(self.collapse, self));
      d.children = null;
    }
  }

  // Toggle children on click.
  click(self, d, tree, svg, i, duration) {
    if (d._children == null && d.children == null) { return; }
    if (d.children) {
      d._children = d.children;
      d.children = null;
    } else {
      d.children = d._children;
      d._children = null;
    }
    self.update(d, tree, svg, i, duration);
  }

  update(source, tree, svg, i, duration) {
    // Assigns the x and y position for the nodes
    const data = tree(this.root);

    // Compute the new tree layout.
    const nodes = data.descendants();
    const links = data.descendants().slice(1);

    let rectWidth = 230; // カードの標準横幅
    let rectHeight = 150; // カードの標準縦幅
    const originalRectHeight = 150; // カードの標準縦幅
    const minWidthMargen = 5; // 最小の横幅のマージン
    const heightRatio = 0.65; // 横に対する縦の比率

    // 縮める場合にカードの縦横の長さを計算
    const nodesLength = nodes.length;
    if (nodesLength > 2) {
      for (let k = 0; k < (nodesLength - 1); k += 1) {
        if (nodes[nodesLength - 1 - k].depth === nodes[nodesLength - 2 - k].depth) {
          const diffWidth = nodes[nodesLength - 1 - k].x - nodes[nodesLength - 2 - k].x;
          if (diffWidth < rectWidth) {
            rectWidth = diffWidth - minWidthMargen; // カードの横幅を調整
            rectHeight = (diffWidth - minWidthMargen) * heightRatio; // カードの縦幅を調整
          }
          k = nodesLength;
        }
      }
    }

    const reductionRatio = rectHeight / originalRectHeight;

    // Normalize for fixed-depth.
    nodes.forEach((d) => { d.y = (d.depth * 220) + (originalRectHeight - rectHeight); });
    nodes.forEach((d) => {
      if (d.depth === 0) {
        d.hidden = true;
      }
    });

    // ****************** Nodes section ***************************

    // Nodeを作成
    // adds each node as a group
    const node = svg.selectAll('g.node')
      .data(nodes, (d) => { return d.id || (d.id = (i += 1)); });

    // Enter any new modes at the parent's previous position.
    const nodeEnter = node.enter()
      .append('g')
      .attr('class', 'node')
      .attr('transform', (d) => {
        return `translate(${d.x},${d.y})`;
      })
      .on('click', d => this.click(this, d, tree, svg, i, duration));

    // カードに影をつける
    this.dropShadow(nodeEnter);

    // カードを作成
    // adds the rectangle to the node
    nodeEnter.append('rect')
      .attr('x', (rectWidth / 2) * -1) // x座標を指定
      .attr('y', rectHeight * -1) // y座標を指定
      .attr('width', rectWidth) // 横幅を指定
      .attr('height', rectHeight) // 縦幅を指定
      .attr('fill', 'white') // 背景色を指定
      .attr('stroke', '#F2F2F2') // 枠線の色を指定
      .attr('stroke-width', 1) // 枠線の太さを指定
      .style('filter', 'url(#drop-shadow)')
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    nodeEnter.append('image')
      .attr('class', 'uim')
      .attr('xlink:href', () => { return 'https://cdn3.iconfinder.com/data/icons/users/100/user_male_1-512.png'; })
      .attr('x', `${-87 * reductionRatio}px`)
      .attr('y', `${-47 * reductionRatio}px`)
      .attr('width', `${32 * reductionRatio}px`)
      .attr('height', `${32 * reductionRatio}px`)
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    nodeEnter.append('image')
      .attr('class', 'menu')
      .attr('xlink:href', () => { return 'https://cdn3.iconfinder.com/data/icons/users/100/user_male_1-512.png'; })
      .attr('x', `${58 * reductionRatio}px`)
      .attr('y', `${-47 * reductionRatio}px`)
      .attr('width', `${32 * reductionRatio}px`)
      .attr('height', `${32 * reductionRatio}px`)
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    // プログレスバーを生成
    this.createProgressBar(nodeEnter, reductionRatio);

    // テキストを作成
    // adds the text to the node
    nodeEnter.append('text')
      .attr('class', 'oname')
      .attr('y', 0)
      .attr('dy', 0)
      .attr('fill', 'grey')
      .style('text-anchor', 'start')
      .style('font-size', `${0.8 * reductionRatio}em`)
      .html((d) => { return this.leftLinebreak(d.data.okrName); })
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    nodeEnter.append('text')
      .attr('class', 'arate')
      .attr('y', `${-65 * reductionRatio}px`)
      .attr('dy', 0)
      .attr('x', `${-87 * reductionRatio}px`)
      .attr('dx', 0)
      .attr('fill', 'grey')
      .style('text-anchor', 'start')
      .style('font-size', `${0.7 * reductionRatio}em`)
      .text((d) => { return `${d.data.achievementRate}%`; })
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    nodeEnter.append('text')
      .attr('class', 'uname')
      .attr('y', `${-26 * reductionRatio}px`)
      .attr('dy', 0)
      .attr('x', `${-46 * reductionRatio}px`)
      .attr('dx', 0)
      .attr('fill', 'grey')
      .style('text-anchor', 'start')
      .style('font-size', `${0.8 * reductionRatio}em`)
      .text((d) => {
        if (d.data.ownerType === '1') {
          return d.data.ownerUserName;
        } else if (d.data.ownerType === '2') {
          return d.data.ownerGroupName;
        } else if (d.data.ownerType === '3') {
          return d.data.ownerCompanyName;
        }
      })
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

    // UPDATE
    const nodeUpdate = nodeEnter.merge(node);

    // カードに影をつける
    this.dropShadow(nodeUpdate);

    // Transition to the proper position for the node
    nodeUpdate.transition()
      .duration(duration)
      .attr('transform', (d) => {
        return `translate(${d.x},${d.y})`;
      });

    // Update the node attributes and style
    nodeUpdate.select('rect')
      .attr('x', (rectWidth / 2) * -1) // x座標を指定
      .attr('y', rectHeight * -1) // y座標を指定
      .attr('width', rectWidth) // 横幅を指定
      .attr('height', rectHeight) // 縦幅を指定
      .style('fill', (d) => {
        return d._children ? '#EFF2FB' : 'white';
      })
      .attr('cursor', 'pointer');

    nodeUpdate.select('image.uim')
      .attr('x', `${-87 * reductionRatio}px`)
      .attr('y', `${-47 * reductionRatio}px`)
      .attr('width', `${32 * reductionRatio}px`)
      .attr('height', `${32 * reductionRatio}px`);

    nodeUpdate.select('image.menu')
      .attr('x', `${58 * reductionRatio}px`)
      .attr('y', `${-47 * reductionRatio}px`)
      .attr('width', `${32 * reductionRatio}px`)
      .attr('height', `${32 * reductionRatio}px`);

    // プログレスバーを生成
    this.createProgressBar(nodeUpdate, reductionRatio);

    nodeUpdate.select('text.oname')
      .style('font-size', `${0.8 * reductionRatio}em`)
      .html((d) => { return this.leftLinebreak(d.data.okrName); });

    nodeUpdate.select('text.arate')
      .attr('y', `${-65 * reductionRatio}px`)
      .attr('x', `${-87 * reductionRatio}px`)
      .style('font-size', `${0.7 * reductionRatio}em`)
      .text((d) => { return `${d.data.achievementRate}%`; });

    nodeUpdate.select('text.uname')
      .attr('y', `${-26 * reductionRatio}px`)
      .attr('x', `${-46 * reductionRatio}px`)
      .style('font-size', `${0.8 * reductionRatio}em`)
      .text((d) => {
        if (d.data.ownerType === '1') {
          return d.data.ownerUserName;
        } else if (d.data.ownerType === '2') {
          return d.data.ownerGroupName;
        } else if (d.data.ownerType === '3') {
          return d.data.ownerCompanyName;
        }
      });

    nodeUpdate.select('text')
      .attr('cursor', 'pointer');

    // Remove any exiting nodes
    const nodeExit = node.exit().transition()
      .duration(duration)
      .attr('transform', () => {
        return `translate(${source.x},${source.y})`;
      })
      .remove();

    // On exit reduce the opacity of text labels
    nodeExit.select('text')
      .style('fill-opacity', 1e-6);

    // ****************** links section ***************************

    // 線でつなく
    // adds the links between the nodes
    const link = svg.selectAll('path.link').data(links, (d) => { return d.id; });

    const linkEnter = link.enter()
      // .append('path')
      .insert('path', 'g')
      .attr('class', 'link')
      .attr('stroke', 'grey') // 枠線の色を指定
      .attr('stroke-width', 1) // 枠線の太さを指定
      .attr('fill', 'none') // 固定値
      .attr('d', (d) => {
        return `M${d.x},${d.y - rectHeight}C${d.x},${((d.y + d.parent.y) / 2) - 70} ${d.parent.x},${((d.y + d.parent.y) / 2) - 70} ${d.parent.x},${d.parent.y}`;
      });

    // UPDATE
    const linkUpdate = linkEnter.merge(link);

    // Transition back to the parent element position
    linkUpdate.transition()
      .duration(duration)
      .attr('d', (d) => {
        return (d.depth === 1 && this.isHidden === true) ? null : `M${d.x},${d.y - rectHeight}C${d.x},${((d.y + d.parent.y) / 2) - 70} ${d.parent.x},${((d.y + d.parent.y) / 2) - 70} ${d.parent.x},${d.parent.y}`;
      })
      .style('display', (d) => {
        return d.data.hidden ? 'none' : '';
      });

      // Remove any exiting links
    link.exit().transition()
        .duration(duration)
        .attr('d', (d) => {
          return `M${d.x},${d.y - rectHeight}C${d.x},${((d.y + d.parent.y) / 2) - 70} ${d.parent.x},${((d.y + d.parent.y) / 2) - 70} ${d.parent.x},${d.parent.y}`;
        })
        .remove();

    // Store the old positions for transition.
    nodes.forEach((d) => {
      d.x0 = d.x;
      d.y0 = d.y;
    });
  }

  renderTree(treeData, svgDomNode) {
    // hidden setting
    this.isHidden = (treeData.hidden !== undefined);

    // Set the dimensions and margins of the diagram
    const marginTop = this.isHidden ? 180 : 180;
    const margin = { top: marginTop, right: 0, bottom: 30, left: 0 }; // SVG描画スペースの余白
    const width = svgDomNode.clientWidth - margin.left - margin.right; // SVG描画スペースの横幅
    const height = 1290 - margin.top - margin.bottom; // SVG描画スペースの縦幅

    // SVGを作成
    // append the svg object to the body of the page
    // appends a 'group' element to 'svg'
    // moves the 'group' element to the top left margin
    const svg = d3.select(svgDomNode)
      .append('svg')
      .attr('width', width + margin.right + margin.left)
      .attr('height', height + margin.top + margin.bottom)
      .append('g')
      .attr('transform', `translate(${margin.left},${margin.top})`);

    const i = 0;
    const duration = 350;

    // Treeを作成
    // declares a tree layout and assigns the size
    const tree = d3.tree().size([height, width]);

    // Assigns parent, children, height, depth
    this.root = d3.hierarchy(treeData, (d) => { return d.children; });
    // const root = d3.hierarchy(treeData);
    this.root.x0 = height / 2;
    this.root.y0 = 0;

    // Collapse after the second level
    const { children = [] } = this.root;
    if (children.length > 0) {
      children.forEach(_.partial(this.collapse, this));
      this.update(this.root, tree, svg, i, duration);
    }
  }

  render() {
    return (
      <div className="tree-container" ref={node => (this.node = node)} />
    );
  }
}
