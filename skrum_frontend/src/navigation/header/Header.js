import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import Select from 'react-select';
import _ from 'lodash';
import { timeframesPropTypes } from './propTypes';

const style = {
  /* -----------------------------------
   *  Main
   *  ----------------------------------- */
  container: {
    display: 'flex',
    backgroundColor: '#004D99',
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
    minWidth: '11.25em',
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
    if (path.startsWith('/group')) {
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

  static propTypes = {
    timeframes: timeframesPropTypes.isRequired,
  };

  render() {
    const { timeframes = [] } = this.props;
    const timeframeOptions = _.orderBy(timeframes, 'timeframeId', 'asc')
      .map(({ timeframeId, timeframeName }) => ({ value: timeframeId, label: timeframeName }));
    const timeframeDefault = (_.find(timeframes, { defaultFlg: 1 }) || {}).timeframeId;
    return (
      <div style={style.subMenu}>
        <Select
          style={style.timePeriod}
          options={timeframeOptions}
          value={timeframeDefault}
          placeholder=""
          clearable={false}
          searchable={false}
        />
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
    activeMenu: PropTypes.oneOf(['objective', 'map', 'timeline', 'control']).isRequired,
    timeframes: timeframesPropTypes,
  };

  isActive(key) {
    return this.props.activeMenu === key;
  }

  render() {
    return (
      <div style={style.container}>
        <Tab title="目標管理" isActive={this.isActive('objective')} to="/objective" />
        <Tab title="マップ" isActive={this.isActive('map')} to="/map" />
        <Tab title="タイムライン" isActive={this.isActive('timeline')} to="/timeline" />
        <Tab title="グループ管理" isActive={this.isActive('control')} to="/control" />
        <div style={style.rightArea}>
          <SubMenu timeframes={this.props.timeframes} />
        </div>
      </div>
    );
  }
}
