import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link, browserHistory } from 'react-router';
import Select from 'react-select';
import _ from 'lodash';
import { tabPropType, timeframesPropTypes } from './propTypes';
import { explodePath, implodePath, replacePath } from '../../util/RouteUtil';
import styles from './Header.css';

class Tab extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    name: tabPropType.isRequired,
  };

  getStyles() {
    const { name } = this.props;
    const { tab } = explodePath();
    return `${styles.tab} ${name === tab ? styles.tabActive : styles.tabNormal}`;
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
        <div className={this.getStyles()}>
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

  static getTimeframeStyle(id, currentId) {
    return `${styles.timeframe} ${id === currentId ? styles.timeframeCurrent : ''}`;
  }

  static timeframeRenderer(currentId, { value: id, label }) {
    return (
      <div className={SubMenu.getTimeframeStyle(id, currentId)}>
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
    const currentTimeframeId = _.toNumber(timeframeId);
    return (
      <div className={styles.subMenu}>
        <Select
          className={styles.timePeriod}
          options={timeframeOptions}
          optionRenderer={_.partial(SubMenu.timeframeRenderer, currentTimeframeId)}
          onChange={SubMenu.handleTimeframeChange}
          value={currentTimeframeId}
          placeholder=""
          clearable={false}
          searchable={false}
        />
        <img
          className={styles.userIcon}
          src="https://cdn3.iconfinder.com/data/icons/users/100/user_male_1-512.png"
          alt=""
        />
        <img
          className={styles.settingIcon}
          src="/img/setting.svg"
          alt=""
        />
        <button onClick={handleLogoutSubmit}>
          <img
            src="/img/logout.svg"
            alt=""
          />
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
    const { section } = explodePath();
    return (
      <div className={styles.container}>
        <Tab title="目標管理" name="objective" />
        <Tab title="マップ" name="map" />
        {section === 'group' ? <Tab title="タイムライン" name="timeline" /> : null}
        {section !== 'company' ? <Tab title="グループ管理" name="control" /> : null}
        <div className={styles.rightArea}>
          <SubMenu {...{ timeframes, handleLogoutSubmit }} />
        </div>
      </div>
    );
  }
}
