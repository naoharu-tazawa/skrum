import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { itemsPropTypes, sectionPropTypes, sectionsPropTypes } from './propTypes';
import EntityLink, { getEntityTypeId } from '../../components/EntityLink';
import { subjectPropType, explodePath, implodePath } from '../../util/RouteUtil';
import { isBasicRoleAndAbove, isAdminRoleAndAbove, isSuperRole } from '../../util/UserUtil';
import styles from './SideBar.css';

class SecttingItem extends Component {
  static propTypes = {
    subject: subjectPropType.isRequired,
    title: PropTypes.string.isRequired,
    pathname: PropTypes.string.isRequired,
    isExpanded: PropTypes.bool.isRequired,
    imageName: PropTypes.string,
  };

  render() {
    const { subject, pathname, title, isExpanded, imageName } = this.props;
    const path = explodePath(pathname);
    const isActive = subject === path.subject;
    const linkStyle = `
      ${styles.settingItem}
      ${isActive && styles.isActive}
      ${isExpanded && styles.isExpanded}
      `;
    const iconSize = '24px';
    return (
      <Link className={linkStyle} to={implodePath({ tab: 'setting', subject })}>
        <img
          src={`/img/${imageName || `${subject}_setting`}.png`}
          alt=""
          title={title}
          style={{ width: iconSize, height: iconSize, minWidth: iconSize, minHeight: iconSize }}
        />
        <label>{title}</label>
      </Link>);
  }
}

class SectionItem extends Component {
  static propTypes = {
    subject: subjectPropType.isRequired,
    id: PropTypes.number.isRequired,
    title: PropTypes.string.isRequired,
    pathname: PropTypes.string.isRequired,
    isExpanded: PropTypes.bool.isRequired,
  };

  render() {
    const { subject, pathname, id, title, isExpanded } = this.props;
    const type = getEntityTypeId(subject);
    const path = explodePath(pathname);
    const isActive = subject === path.subject && id === path.id;
    const linkStyle = `
      ${styles.sectionItem}
      ${isActive && styles.isActive}
      ${isExpanded && styles.isExpanded}
      `;
    const iconSize = '36px';
    return (
      <EntityLink
        componentClassName={linkStyle}
        entity={{ id, name: title, type }}
        avatarSize={iconSize}
      />);
  }
}

class Section extends Component {
  static propTypes = {
    subject: subjectPropType.isRequired,
    title: PropTypes.string.isRequired,
    items: itemsPropTypes.isRequired,
    pathname: PropTypes.string.isRequired,
    isExpanded: PropTypes.bool.isRequired,
  };

  render() {
    const { subject, title, items, pathname, isExpanded } = this.props;
    return (
      <div className={styles.sectionContainer}>
        <div className={`${styles.sectionTitle} ${isExpanded && styles.isExpanded}`}>{title}</div>
        {items.map(({ id, title: itemTitle }) =>
          <SectionItem key={id} {...{ subject, id, title: itemTitle, pathname, isExpanded }} />)}
      </div>);
  }
}

export default class SideBar extends Component {

  static propTypes = {
    top: PropTypes.string.isRequired,
    pathname: PropTypes.string.isRequired,
    roleLevel: PropTypes.number.isRequired,
    userSection: sectionPropTypes.isRequired,
    groupSections: sectionsPropTypes.isRequired,
    companyId: PropTypes.number,
    companyName: PropTypes.string,
  };

  getBaseStyles = isExpanded =>
    `${styles.component} ${isExpanded && styles.isExpanded}`;

  renderSection = (subject, { title, items }, pathname, isExpanded) =>
    <Section
      key={`${subject}-${title}`}
      {...{ subject, title, items, pathname, isExpanded }}
    />;

  render() {
    const { top, pathname, roleLevel, userSection, groupSections,
      companyId, companyName } = this.props;
    const { isExpanded = true } = this.state || {};
    const { tab } = explodePath(pathname);
    const isSetting = tab === 'setting';
    const showUser = !isSetting && tab !== 'timeline';
    const showGroups = !isSetting;
    const showCompany = !isSetting && companyId && /^(objective|map|timeline)$/.test(tab);
    return (
      <div className={this.getBaseStyles(isExpanded)}>
        <div className={`${styles.header} ${isExpanded && styles.isExpanded}`}>
          <Link to={top}>
            <img
              className={styles.headerLogo}
              src="/img/skrum_logo.svg"
              alt="Skrum"
            />
          </Link>
        </div>
        <div className={styles.toggleArea}>
          <button
            onClick={() => this.setState({ isExpanded: !isExpanded })}
            className={styles.toggleButton}
          >
            {isExpanded ? '<' : '>'}
          </button>
        </div>
        {showUser && this.renderSection('user', userSection, pathname, isExpanded)}
        {showGroups && groupSections.map(section =>
          this.renderSection('group', section, pathname, isExpanded))}
        {showCompany && (
          <SectionItem
            subject="company"
            id={companyId}
            title={companyName}
            pathname={pathname}
            isExpanded={isExpanded}
          />)}
        {isSetting && isAdminRoleAndAbove(roleLevel) && (
          <SecttingItem subject="user" title="ユーザ" {...{ pathname, isExpanded }} />)}
        {isSetting && isBasicRoleAndAbove(roleLevel) && (
          <SecttingItem subject="group" title="グループ" {...{ pathname, isExpanded }} />)}
        {isSetting && isSuperRole(roleLevel) && (
          <SecttingItem subject="company" title="会社情報変更" {...{ pathname, isExpanded }} />)}
        {isSetting && isSuperRole(roleLevel) && (
          <SecttingItem subject="timeframe" title="目標期間設定" {...{ pathname, isExpanded }} />)}
        {isSetting && isBasicRoleAndAbove(roleLevel) && (
          <SecttingItem subject="email" title="メール設定" {...{ pathname, isExpanded }} />)}
        {isSetting && isBasicRoleAndAbove(roleLevel) && (
          <SecttingItem subject="account" title="パスワード" {...{ pathname, isExpanded }} imageName="password_change" />)}
      </div>
    );
  }
}
