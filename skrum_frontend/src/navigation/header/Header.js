import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link, browserHistory } from 'react-router';
import { explodePath, implodePath, replacePath } from '../../util/RouteUtil';
import TimeframesDropdown from '../../components/TimeframesDropdown';
import styles from './Header.css';

class Tab extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    name: PropTypes.oneOf(['o', 'objective', 'm', 'map', 't', 'timeline', 'c', 'control']).isRequired,
  };

  getStyles() {
    const { name } = this.props;
    const { tab } = explodePath();
    return `${styles.tab} ${name === tab ? styles.tabActive : styles.tabNormal}`;
  }

  getPath() {
    const { name: tab } = this.props;
    const { subject, ...others } = explodePath();
    // if (/^(user|group|company)$/.test(subject)) {
    return implodePath({ ...others, subject, tab }, { basicOnly: true });
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
    handleLogoutSubmit: PropTypes.func.isRequired,
    onAdd: PropTypes.func.isRequired,
  };

  render() {
    const { onAdd, handleLogoutSubmit } = this.props;
    return (
      <div className={styles.subMenu}>
        <TimeframesDropdown
          plain
          onChange={({ value: timeframeId }) => browserHistory.push(replacePath({ timeframeId }))}
        />
        <button onClick={onAdd}>
          <img
            className={styles.addIcon}
            src="/img/common/icn_add.png"
            alt=""
          />
        </button>
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
    onAdd: PropTypes.func.isRequired,
    handleLogoutSubmit: PropTypes.func.isRequired,
  };

  render() {
    const { onAdd, handleLogoutSubmit } = this.props;
    const { subject } = explodePath();
    return (
      <div className={styles.container}>
        <Tab title="目標管理" name="objective" />
        <Tab title="マップ" name="map" />
        {subject === 'group' && <Tab title="タイムライン" name="timeline" />}
        {subject !== 'company' && <Tab title="グループ管理" name="control" />}
        <div className={styles.rightArea}>
          <SubMenu {...{ onAdd, handleLogoutSubmit }} />
        </div>
      </div>
    );
  }
}
