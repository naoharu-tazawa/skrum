import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link, browserHistory } from 'react-router';
import TimeframesDropdown from '../../components/TimeframesDropdown';
import DropdownMenu from '../../components/DropdownMenu';
import EntityLink, { EntityType } from '../../components/EntityLink';
import { tabPropType, explodePath, implodePath, replacePath } from '../../util/RouteUtil';
import { isBasicRole } from '../../util/UserUtil';
import styles from './Header.css';

class Tab extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    name: tabPropType.isRequired,
    pathname: PropTypes.string.isRequired,
    local: PropTypes.bool,
  };

  getStyles() {
    const { pathname, name } = this.props;
    const { tab } = explodePath(pathname);
    return `${styles.tab} ${name === tab ? styles.tabActive : styles.tabNormal}`;
  }

  getPath() {
    const { pathname, name: tab } = this.props;
    const { subject, ...others } = explodePath(pathname);
    // if (/^(user|group|company)$/.test(subject)) {
    return implodePath({ ...others, tab, subject }, { basicOnly: true });
    // }
  }

  render() {
    const { title, local } = this.props;
    const titleContent = (
      <div className={this.getStyles()}>
        {title}
      </div>);
    return local ? titleContent : (
      <Link to={this.getPath()}>
        {titleContent}
      </Link>);
  }
}

class SubMenu extends Component {

  static propTypes = {
    isSetting: PropTypes.bool.isRequired,
    currentUserId: PropTypes.number.isRequired,
    roleLevel: PropTypes.number.isRequired,
    handleLogoutSubmit: PropTypes.func.isRequired,
    onAdd: PropTypes.func.isRequired,
  };

  render() {
    const { isSetting, currentUserId, roleLevel, onAdd, handleLogoutSubmit } = this.props;
    const settingLink = isBasicRole(roleLevel) ? '/s/g' : '/s/u';
    return (
      <div className={styles.subMenu}>
        {!isSetting && <TimeframesDropdown
          plain
          onChange={({ value: timeframeId }) => browserHistory.push(replacePath({ timeframeId }))}
        />}
        {!isSetting && <button className={styles.addOKR} onClick={onAdd}>
          <span className={styles.circle}>
            <img src="/img/common/icn_add.png" alt="Add" />
          </span>
        </button>}
        <DropdownMenu
          trigger={(
            <EntityLink
              entity={{ id: currentUserId, type: EntityType.USER }}
              avatarOnly
              avatarSize="30px"
              local
            />)}
          options={[
            { caption: isSetting ? '設定完了' : '設定',
              path: isSetting ? '/o/u' : settingLink },
            { caption: 'ログアウト', onClick: handleLogoutSubmit },
          ]}
          align="right"
        />
      </div>);
  }
}

export default class Header extends Component {

  static propTypes = {
    pathname: PropTypes.string.isRequired,
    currentUserId: PropTypes.number.isRequired,
    roleLevel: PropTypes.number.isRequired,
    onAdd: PropTypes.func.isRequired,
    handleLogoutSubmit: PropTypes.func.isRequired,
  };

  render() {
    const { pathname, currentUserId, roleLevel, onAdd, handleLogoutSubmit } = this.props;
    const { tab, subject } = explodePath(pathname);
    const isSetting = tab === 'setting';
    return (
      <div className={styles.container}>
        {isSetting && <Tab title="設定" name="setting" pathname={pathname} local />}
        {!isSetting && <Tab title="目標管理" name="objective" pathname={pathname} />}
        {!isSetting && <Tab title="マップ" name="map" pathname={pathname} />}
        {!isSetting && subject !== 'user' && <Tab title="タイムライン" name="timeline" pathname={pathname} />}
        {!isSetting && subject !== 'company' && <Tab title="グループ管理" name="control" pathname={pathname} />}
        <div className={styles.rightArea}>
          <SubMenu {...{ isSetting, currentUserId, roleLevel, onAdd, handleLogoutSubmit }} />
        </div>
      </div>
    );
  }
}
