import React, { Component, PropTypes } from 'react';

export default class SampleComponent extends Component {
  static propTypes = {
    count: PropTypes.number,
    handleClick: PropTypes.func.isRequired,
  };
  static defaultProps = {
    count: 0,
  };

  render() {
    return (<div>
      <button onClick={this.props.handleClick}>add!</button>
      <br />
      <pre>
        {this.props.count}
      </pre>
    </div>);
  }
}
