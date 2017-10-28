import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { GatewayProvider, GatewayDest } from 'react-gateway';

export default class App extends Component {
  static propTypes = {
    children: PropTypes.element,
  };

  render() {
    return (
      <GatewayProvider>
        <div>
          {this.props.children}
          <GatewayDest name="global" component="section" />
        </div>
      </GatewayProvider>
    );
  }
}
