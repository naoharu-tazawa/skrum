import React, { Component } from 'react';
import * as d3 from 'd3';
// import { tree } from 'd3-hierarchy';
import { d3treesPropTypes } from './propTypes';

export default class D3Tree extends Component {

  static propTypes = {
    map: d3treesPropTypes.isRequired,
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
    const margin = { top: 200, right: 90, bottom: 30, left: 90 };
    const width = 960 - margin.left - margin.right;
    const height = 500 - margin.top - margin.bottom;

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
    const treemap = d3.tree().size([height, width]);

    // Assigns parent, children, height, depth
    const root = d3.hierarchy(treeData, (d) => { return d.children; });
    root.x0 = height / 2;
    root.y0 = 0;

    // Collapse after the second level
    // root.children.forEach(this.collapse());

    // update(root);

    // Assigns the x and y position for the nodes
    const data = treemap(root);

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
      .enter().append('path')
      .attr('class', 'link')
      .attr('d', (d) => {
        return `M${d.x},${d.y}C${d.x},${((d.y + d.parent.y) / 2)} ${d.parent.x},${((d.y + d.parent.y) / 2)} ${d.parent.x},${d.parent.y}`;
      });

    // Nodeを作成
    // adds each node as a group
    const node = svg.selectAll('g.node')
      .data(nodes)
      .enter().append('g')
      .attr('class', (d) => {
        return `node${d.children ? ' node--internal' : ' node--leaf'}`;
      })
      .attr('transform', (d) => {
        return `translate(${d.x},${d.y})`;
      });
    console.log(node);

    // 円を作成
    // adds the circle to the node
    node.append('circle').attr('r', 10);

    // テキストを作成
    // adds the text to the node
    node.append('text')
      .attr('dy', '.35em')
      .attr('y', (d) => { return d.children ? -20 : 20; })
      .style('text-anchor', 'middle')
      .text((d) => { return d.data.name; });

    return '';
  }

  render() {
    return (
      <div className="tree-container" ref={node => (this.node = node)} />
    );
  }
}
