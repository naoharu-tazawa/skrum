import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Header from './Header';
import { timeframesPropTypes } from './propTypes';
import { logout } from '../../auth/action';

const menus = [
  {
    path: 'objective',
    key: 'objective',
  },
  {
    path: 'map',
    key: 'map',
  },
  {
    path: 'timeline',
    key: 'timeline',
  },
  {
    path: 'control',
    key: 'control',
  },
];

class HeaderContainer extends Component {

  static propTypes = {
    timeframes: timeframesPropTypes.isRequired,
    dispatchLogout: PropTypes.func,
  };

  getActiveMenu() {
    const path = window.location.pathname;
    const { key = 'objective' } = menus.find(menu => path.endsWith(menu.path)) || {};
    return key;
  }

  render() {
    const { timeframes } = this.props;
    return (
      <Header
        activeMenu={this.getActiveMenu()} timeframes={timeframes}
        handleLogoutSubmit={this.props.dispatchLogout}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { timeframes = [] } = state.user.data || {};
  return { timeframes };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchLogout = () =>
    dispatch(logout());
  return { dispatchLogout };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(HeaderContainer);
