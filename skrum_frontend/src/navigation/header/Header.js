import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';

const style = {
  /* -----------------------------------
   *  Main
   *  ----------------------------------- */
  container: {
    display: 'flex',
    backgroundColor: '#094C92',
    padding: '0 60px',
    boxShadow: '2px 1px 6px 0px #000',
  },
  rightArea: {
    marginLeft: 'auto',
  },
  tab: {
    cursor: 'pointer',
    color: '#739BC3',
  },
  tabNormal: {
    padding: '20px 20px',
  },
  tabActive: {
    borderBottom: '6px solid #fff',
    color: '#fff',
    padding: '20px 20px 14px 20px',
  },
  subMenu: {
    color: '#fff',
    display: 'flex',
    alignItems: 'center',
    height: '100%',
  },
  timePeriod: {
    marginRight: '10px',
  },
  userIcon: {
    width: '20px',
    height: '20px',
    margin: '0 10px',
    backgroundColor: '#fff',
    borderRadius: '50%',
    border: '1px solid #fff',
  },
  settingIcon: {
    width: '20px',
    height: '20px',
    margin: '0 10px',
  },
};

class Tab extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    isActive: PropTypes.bool,
    to: PropTypes.string.isRequired,
  };

  static defaultProp = {
    isActive: false,
  };

  getStyle() {
    const { isActive } = this.props;
    return Object.assign({}, style.tab, isActive ? style.tabActive : style.tabNormal);
  }

  getTo() {
    const path = window.location.pathname;
    const { to } = this.props;
    if (path.startsWith('/team')) {
      const bases = path.split('/');
      return `/${bases[1]}/${bases[2]}${to}`;
    }
  }

  render() {
    return (
      <Link to={this.getTo()}>
        <div style={this.getStyle()}>
          {this.props.title}
        </div>
      </Link>);
  }
}

class SubMenu extends Component {
  render() {
    return (
      <div style={style.subMenu}>
        <p style={style.timePeriod}>2017/1Q ▼</p>
        <img
          style={style.userIcon}
          src="https://cdn3.iconfinder.com/data/icons/users/100/user_male_1-512.png"
          alt=""
        />
        <img
          style={style.settingIcon}
          src="http://www.iconsdb.com/icons/preview/white/gear-2-xxl.png"
          alt=""
        />
      </div>);
  }
}

export default class Header extends Component {

  static propTypes = {
    activeMenu: PropTypes.oneOf(['okr', 'map', 'tl', 'gr']).isRequired,
  };

  isActive(key) {
    return this.props.activeMenu === key;
  }

  render() {
    return (
      <div style={style.container}>
        <Tab title="目標管理" isActive={this.isActive('okr')} to="/okr" />
        <Tab title="マップ" isActive={this.isActive('map')} to="/map" />
        <Tab title="タイムライン" isActive={this.isActive('tl')} to="/timeline" />
        <Tab title="グループ管理" isActive={this.isActive('gr')} to="/group" />

        <div style={style.rightArea}>
          <SubMenu />
        </div>
      </div>
    );
  }
}
