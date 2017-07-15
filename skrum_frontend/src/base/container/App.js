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
          <div>
            {this.props.children}
          </div>
          <GatewayDest name="modal" className="modal-container" />
        </div>
      </GatewayProvider>
    );
  }
}
