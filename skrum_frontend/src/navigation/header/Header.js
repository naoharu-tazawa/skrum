import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link, browserHistory } from 'react-router';
import Select from 'react-select';
import _ from 'lodash';
import { tabPropType, timeframesPropTypes } from './propTypes';
import { explodePath, implodePath, replacePath } from '../../util/RouteUtil';

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
    name: tabPropType.isRequired,
  };

  getStyle() {
    const { name } = this.props;
    const { tab } = explodePath();
    return { ...style.tab, ...(name === tab ? style.tabActive : style.tabNormal) };
  }

  getPath() {
    const { name: tab } = this.props;
    const { section, ...others } = explodePath();
    // if (/^(user|group|company)$/.test(section)) {
    return implodePath({ ...others, section, tab });
    // }
  }

  render() {
    const { title } = this.props;
    return (
      <Link to={this.getPath()}>
        <div style={this.getStyle()}>
          {title}
        </div>
      </Link>);
  }
}

class SubMenu extends Component {

  static propTypes = {
    timeframes: timeframesPropTypes.isRequired,
    handleLogoutSubmit: PropTypes.func.isRequired,
  };

  static getTimeframeStyle() {
    return { ...style.tab };
  }

  static timeframeRenderer({ value: timeframeId, label }) {
    return (
      <div style={SubMenu.getTimeframeStyle(timeframeId)}>
        {label}
      </div>);
  }

  static handleTimeframeChange({ value: timeframeId }) {
    browserHistory.push(replacePath({ timeframeId }));
  }

  render() {
    const { timeframes, handleLogoutSubmit } = this.props;
    const timeframeOptions = _.orderBy(timeframes, 'timeframeId', 'asc')
      .map(({ timeframeId: value, timeframeName: label }) => ({ value, label }));
    const { timeframeId } = explodePath();
    return (
      <div style={style.subMenu}>
        <Select
          style={style.timePeriod}
          options={timeframeOptions}
          optionRenderer={SubMenu.timeframeRenderer}
          onChange={SubMenu.handleTimeframeChange}
          value={_.toNumber(timeframeId)}
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
        <button onClick={handleLogoutSubmit}>
          Logout
        </button>
      </div>);
  }
}

export default class Header extends Component {

  static propTypes = {
    timeframes: timeframesPropTypes,
    handleLogoutSubmit: PropTypes.func.isRequired,
  };

  render() {
    const { timeframes = [], handleLogoutSubmit } = this.props;
    return (
      <div style={style.container}>
        <Tab title="目標管理" name="objective" />
        <Tab title="マップ" name="map" />
        <Tab title="タイムライン" name="timeline" />
        <Tab title="グループ管理" name="control" />
        <div style={style.rightArea}>
          <SubMenu {...{ timeframes, handleLogoutSubmit }} />
        </div>
      </div>
    );
  }
}
