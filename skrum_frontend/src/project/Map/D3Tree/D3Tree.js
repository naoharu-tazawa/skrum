import React, { Component } from 'react';
import * as d3 from 'd3';
import { d3treePropTypes } from './propTypes';

export default class D3Tree extends Component {

  static propTypes = {
    map: d3treePropTypes.isRequired,
  };

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
  dropShadow(node) {
    const defs = node.append('defs');

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
  // createProgressBar(node) {
  //   const states = ['started', 'inProgress', 'completed'];
  //   const segmentWidth = 100;
  //   const currentState = 'started';
  //
  //   const colorScale = d3.scale.ordinal()
  //     .domain(states)
  //     .range(['yellow', 'orange', 'green']);
  //
  //   node.append('rect')
  //     .attr('class', 'bg-rect')
  //     .attr('rx', 10)
  //     .attr('ry', 10)
  //     .attr('fill', 'gray')
  //     .attr('height', 15)
  //     .attr('width', () => {
  //       return segmentWidth * states.length;
  //     })
  //     .attr('x', 0);
  //
  //   const progress = node.append('rect')
  //     .attr('class', 'progress-rect')
  //     .attr('fill', () => {
  //       return colorScale(currentState);
  //     })
  //     .attr('height', 15)
  //     .attr('width', 0)
  //     .attr('rx', 10)
  //     .attr('ry', 10)
  //     .attr('x', 0);
  //
  //   progress.transition()
  //     .duration(1000)
  //     .attr('width', () => {
  //       const index = states.indexOf(currentState);
  //       return (index + 1) * segmentWidth;
  //     });
  // }

  // Collapse the node and all it's children
  // collapse(d) {
  //   if (d.children) {
  //     d._children = d.children;
  //     d._children.forEach(this.collapse());
  //     d.children = null;
  //   }
  // }

  renderTree(treeData, svgDomNode) {
    // Set the dimensions and margins of the diagram
    const margin = { top: 180, right: 90, bottom: 30, left: 90 }; // SVG描画スペースの余白
    const width = svgDomNode.clientWidth - margin.left - margin.right; // SVG描画スペースの横幅
    const height = 1200 - margin.top - margin.bottom; // SVG描画スペースの縦幅

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

    // const i = 0;
    // const duration = 750;
    // const root;

    // Treeを作成
    // declares a tree layout and assigns the size
    const tree = d3.tree().size([height, width]);

    // Assigns parent, children, height, depth
    const root = d3.hierarchy(treeData, (d) => { return d.children; });
    // const root = d3.hierarchy(treeData);
    root.x0 = height / 2;
    root.y0 = 0;

    // Collapse after the second level
    // root.children.forEach(this.collapse());

    // update(root);

    // Assigns the x and y position for the nodes
    const data = tree(root);

    // Compute the new tree layout.
    const nodes = data.descendants();
    const links = data.descendants().slice(1);

    let rectWidth = 230; // カードの標準横幅
    let rectHeight = 150; // カードの標準縦幅
    const minWidthMargen = 5; // 最小の横幅のマージン
    const heightRatio = 0.65; // 横に対する縦の比率

    // 縮める場合にカードの縦横の長さを計算
    const nodesLength = nodes.length;
    if (nodesLength > 2) {
      for (let i = 0; i < (nodesLength - 1); i += 1) {
        if (nodes[nodesLength - 1 - i].depth === nodes[nodesLength - 2 - i].depth) {
          const diffWidth = nodes[nodesLength - 1 - i].x - nodes[nodesLength - 2 - i].x;
          if (diffWidth < rectWidth) {
            rectWidth = diffWidth - minWidthMargen; // カードの横幅を調整
            rectHeight = (diffWidth - minWidthMargen) * heightRatio; // カードの縦幅を調整
          }
          i = nodesLength;
        }
      }
    }

    // Normalize for fixed-depth.
    // nodes.forEach((d) => { d.y = d.depth * 180; });

    // ****************** Nodes section ***************************

    // 線でつなく
    // adds the links between the nodes
    svg.selectAll('.link')
      .data(links)
      .enter()
      .append('path')
      .attr('class', 'link')
      .attr('stroke', 'grey') // 枠線の色を指定
      .attr('stroke-width', 1) // 枠線の太さを指定
      .attr('fill', 'none') // 固定値
      .attr('d', (d) => {
        const dy = d.y / 2;
        const dparenty = d.parent.y / 2;
        return `M${d.x},${dy - rectHeight}C${d.x},${((dy + dparenty) / 2) - 70} ${d.parent.x},${((dy + dparenty) / 2) - 70} ${d.parent.x},${dparenty}`;
      });

    // Nodeを作成
    // adds each node as a group
    const node = svg.selectAll('g.node')
      .data(nodes)
      .enter()
      .append('g')
      .attr('class', (d) => {
        return `node${d.children ? ' node--internal' : ' node--leaf'}`;
      })
      .attr('transform', (d) => {
        return `translate(${d.x},${d.y / 2})`;
      });

    // カードに影をつける
    this.dropShadow(node);

    // カードを作成
    // adds the rectangle to the node
    node.append('rect')
      .attr('x', (rectWidth / 2) * -1) // x座標を指定
      .attr('y', rectHeight * -1) // y座標を指定
      .attr('width', rectWidth) // 横幅を指定
      .attr('height', rectHeight) // 縦幅を指定
      .attr('fill', 'white') // 背景色を指定
      .attr('stroke', '#F2F2F2') // 枠線の色を指定
      .attr('stroke-width', 1) // 枠線の太さを指定
      .style('filter', 'url(#drop-shadow)');

    node.append('image')
      .attr('xlink:href', () => { return 'https://cdn3.iconfinder.com/data/icons/users/100/user_male_1-512.png'; })
      .attr('x', '-87px')
      .attr('y', '-47px')
      .attr('width', '32px')
      .attr('height', '32px');

    node.append('image')
      .attr('xlink:href', () => { return 'https://cdn3.iconfinder.com/data/icons/users/100/user_male_1-512.png'; })
      .attr('x', '58px')
      .attr('y', '-47px')
      .attr('width', '32px')
      .attr('height', '32px');

    // プログレスバーを生成
    // this.createProgressBar(node);

    // テキストを作成
    // adds the text to the node
    node.append('text')
      .attr('y', 0)
      .attr('dy', 0)
      .attr('fill', 'grey')
      .style('text-anchor', 'start')
      .style('font-size', '0.8em')
      .html((d) => { return this.leftLinebreak(d.data.okrName); });
      // .text((d) => { return d.data.okrName; });
    node.append('text')
      .attr('y', '-65px')
      .attr('dy', 0)
      .attr('x', '-87px')
      .attr('dx', 0)
      .attr('fill', 'grey')
      .style('text-anchor', 'start')
      .style('font-size', '0.7em')
      .text((d) => { return `${d.data.achievementRate}%`; });

    node.append('text')
      .attr('y', '-26px')
      .attr('dy', 0)
      .attr('x', '-46px')
      .attr('dx', 0)
      .attr('fill', 'grey')
      .style('text-anchor', 'start')
      .style('font-size', '0.8em')
      .text((d) => { return d.data.ownerUserName; });

    return '';
  }

  render() {
    return (
      <div className="tree-container" ref={node => (this.node = node)} />
    );
  }
}
