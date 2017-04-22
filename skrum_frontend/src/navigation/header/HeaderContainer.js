import React, { Component } from 'react';
import Header from './Header';

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


export default class HeaderContainer extends Component {

  getActiveMenu() {
    const path = window.location.pathname;
    const { key = 'okr' } = menus.find(menu => path.endsWith(menu.path)) || {};
    return key;
  }

  render() {
    return (
      <Header activeMenu={this.getActiveMenu()} />
    );
  }
}
