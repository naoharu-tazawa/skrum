import React, { Component } from 'react';
import { connect } from 'react-redux';
import Header from './Header';
import { timeframesPropTypes } from './propTypes';

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
  };

  getActiveMenu() {
    const path = window.location.pathname;
    const { key = 'objective' } = menus.find(menu => path.endsWith(menu.path)) || {};
    return key;
  }

  render() {
    const { timeframes } = this.props;
    return (
      <Header activeMenu={this.getActiveMenu()} timeframes={timeframes} />
    );
  }
}

const mapStateToProps = (state) => {
  const { timeframes = [] } = state.user.data || {};
  return { timeframes };
};

export default connect(
  mapStateToProps,
)(HeaderContainer);
