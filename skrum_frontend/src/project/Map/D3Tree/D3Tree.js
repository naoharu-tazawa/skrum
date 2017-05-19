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
    const rectWidth = 230; // 四角形の横幅
    const rectHeight = 150; // 四角形の縦幅

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
    // const root = d3.hierarchy(treeData, (d) => { return d.children; });
    const root = d3.hierarchy(treeData);
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
        return `M${d.x},${d.y - rectHeight}C${d.x},${((d.y + d.parent.y) / 2)} ${d.parent.x},${((d.y + d.parent.y) / 2)} ${d.parent.x},${d.parent.y}`;
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
        return `translate(${d.x},${d.y})`;
      });
    console.log(node);

    // 四角形を作成
    // adds the rectangle to the node
    node.append('rect')
      .attr('x', (rectWidth / 2) * -1) // x座標を指定
      .attr('y', rectHeight * -1) // y座標を指定
      .attr('width', rectWidth) // 横幅を指定
      .attr('height', rectHeight) // 縦幅を指定
      .attr('fill', 'white') // 背景色を指定
      .attr('stroke', '#F2F2F2') // 枠線の色を指定
      .attr('stroke-width', 1); // 枠線の太さを指定

    // テキストを作成
    // adds the text to the node
    node.append('text')
      .attr('dy', '.350em')
      .attr('y', '-130px')
      .style('text-anchor', 'middle')
      .text((d) => { return d.data.okrName; });
    node.append('text')
      .style('text-anchor', 'start')
      .text((d) => { return d.data.achievementRate; });

    return '';
  }

  render() {
    return (
      <div className="tree-container" ref={node => (this.node = node)} />
    );
  }
}
