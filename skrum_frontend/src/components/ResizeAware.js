//
// react-resize-aware component
//
// Triggers a `resize` event everytime the component changes its bounds
// MIT License
// Copyright 2016, Federico Zivolo
//

import { Component, createElement, Children, isValidElement, cloneElement } from 'react';
import PropTypes from 'prop-types';
import { isFunction } from 'lodash';

const style = {
  display: 'block',
  position: 'absolute',
  top: 0,
  left: 0,
  height: '100%',
  width: '100%',
  overflow: 'hidden',
  pointerEvents: 'none',
  zIndex: -1,
};

export default class ResizeAware extends Component {

  static propTypes = {
    onResize: PropTypes.func,
    onlyEvent: PropTypes.bool,
    component: PropTypes.oneOfType([PropTypes.func, PropTypes.string]),
    children: PropTypes.node,
  };

  // Init the resizeElement
  componentDidMount() {
    this.resizeElement.data = 'about:blank';
  }

  componentWillUnmount() {
    const { resizeTarget } = this.state || {};
    if (resizeTarget) {
      resizeTarget.removeEventListener('resize', this.handleResize);
    }
  }

  // Called when the object is loaded
  handleObjectLoad = (evt) => {
    this.setState({ resizeTarget: evt.target.contentDocument.defaultView },
      () => {
        this.state.resizeTarget.addEventListener('resize', this.handleResize);
        this.handleResize();
      },
    );
  };

  // Function called on component resize
  handleResize = () => {
    const bounds = {
      left: this.container.offsetLeft,
      top: this.container.offsetTop,
      width: this.container.offsetWidth,
      height: this.container.offsetHeight,
    };
    this.setState(bounds);
    if (this.props.onResize) this.props.onResize(bounds);
  };

  render() {
    // eslint-disable-next-line no-unused-vars
    const { component = 'div', children, onlyEvent, onResize, ...props } = this.props;
    const { left, top, width, height } = this.state || {};
    const hasCustomComponent = isFunction(component);
    const bounds = { left, top, width, height };
    return createElement(
      component,
      {
        [hasCustomComponent ? 'getRef' : 'ref']: el => (this.container = el),
        ...(hasCustomComponent && bounds),
        ...props,
      },
      createElement('object', {
        type: 'text/html',
        style,
        ref: el => (this.resizeElement = el),
        onLoad: this.handleObjectLoad,
        'aria-hidden': true,
        tabIndex: -1,
      }),
      Children.map(
        children,
        child => (isValidElement(child) ? cloneElement(child, !onlyEvent ? bounds : null) : child),
      ),
    );
  }
}

export function makeResizeAware(component) {
  return props => createElement(ResizeAware, { component, ...props });
}
