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
    const href = window.location.href;
    const { key } = menus.find(menu => href.endsWith(menu.path));
    return key;
  }

  render() {
    return (
      <Header activeMenu={this.getActiveMenu()} />
    );
  }
}
