import React, { Component } from 'react';
import { connect } from 'react-redux';
import Header from './Header';
import { timeframesPropTypes } from './propTypes';

const menus = [
  {
    path: 'okr',
    key: 'okr',
  },
  {
    path: 'map',
    key: 'map',
  },
  {
    path: 'timeline',
    key: 'tl',
  },
  {
    path: 'group',
    key: 'gr',
  },
];


class HeaderContainer extends Component {

  static propTypes = {
    timeframes: timeframesPropTypes.isRequired,
  };

  getActiveMenu() {
    const path = window.location.pathname;
    const { key = 'okr' } = menus.find(menu => path.endsWith(menu.path)) || {};
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
