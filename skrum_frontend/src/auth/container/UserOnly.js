import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { browserHistory } from 'react-router';
import NavigationContainer from '../../navigation/NavigationContainer';

export default class UserOnly extends Component {
  static propTypes = {
    children: PropTypes.element.isRequired,
  };

  static transfer(props) {
    if (!props.auth.isLoggedIn) {
      browserHistory.push('/login');
    }
  }

  componentWillMount() {
    // UserOnly.transfer(this.props);
  }

  componentWillUpdate() {
    // UserOnly.transfer(nextProps);
  }

  render() {
    return (
      <NavigationContainer>
        {this.props.children}
      </NavigationContainer>);
  }
}
