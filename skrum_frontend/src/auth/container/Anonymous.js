import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import LoginContainer from './LoginContainer';

class Anonymous extends Component {
  static propTypes = {
    top: PropTypes.string.isRequired,
    isAuthorized: PropTypes.bool,
  };

  componentWillMount() {
    this.transfer();
  }

  componentWillReceiveProps() {
    this.transfer();
  }

  transfer() {
    console.log(this.props);
    const { top, isAuthorized } = this.props;
    if (isAuthorized) {
      browserHistory.push(top);
    }
  }

  render() {
    return (<LoginContainer />);
  }
}

const mapStateToProps = (state) => {
  const { isAuthorized } = state.auth;
  return { isAuthorized };
};

export default connect(mapStateToProps)(Anonymous);
