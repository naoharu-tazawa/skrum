import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Header from './Header';
import { timeframesPropTypes } from './propTypes';
import { logout } from '../../auth/action';

class HeaderContainer extends Component {

  static propTypes = {
    timeframes: timeframesPropTypes,
    dispatchLogout: PropTypes.func,
    pathname: PropTypes.string,
  };

  render() {
    const { timeframes = [] } = this.props;
    return (
      <Header
        timeframes={timeframes}
        handleLogoutSubmit={this.props.dispatchLogout}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { timeframes = [] } = state.top.data || {};
  return { timeframes };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchLogout = () => dispatch(logout());
  return { dispatchLogout };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(HeaderContainer);
