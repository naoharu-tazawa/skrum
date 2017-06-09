import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { sectionPropType, itemsPropTypes, sectionPropTypes, sectionsPropTypes } from './propTypes';
import { explodePath, replacePath } from '../../util/RouteUtil';
import styles from './SideBar.css';

class SectionItem extends Component {
  static propTypes = {
    section: sectionPropType.isRequired,
    id: PropTypes.number.isRequired,
    title: PropTypes.string.isRequired,
    imgSrc: PropTypes.string,
    isExpanded: PropTypes.bool.isRequired,
  };

  getStyles() {
    const { section, id } = this.props;
    const { subject, id: currentId } = explodePath();
    const isActive = subject === section && `${id}` === currentId;
    return `${styles.sectionItem} ${isActive ? styles.isActive : ''}`;
  }

  getPath() {
    const { section, id } = this.props;
    // if (/^(user|group|company)$/.test(section)) {
    return replacePath({ subject: section, id }, { basicOnly: true });
    // }
  }

  renderImg() {
    const { imgSrc } = this.props;
    return (!imgSrc) ? null : (
      <img src={imgSrc} alt="" className={styles.sectionItemImg} />);
  }

  render() {
    const { title, isExpanded } = this.props;
    return (<li className={this.getStyles()}>
      {!isExpanded ? null : (
        <Link to={this.getPath()} className={styles.sectionItemLink}>
          {title}
        </Link>)}
      {this.renderImg()}
    </li>);
  }
}

class Section extends Component {
  static propTypes = {
    section: sectionPropType.isRequired,
    title: PropTypes.string.isRequired,
    items: itemsPropTypes.isRequired,
    isExpanded: PropTypes.bool.isRequired,
  };

  renderItem(isExpanded) {
    const { section, items } = this.props;
    return items.map(({ id, title, imgSrc }) => {
      return <SectionItem key={id} {...{ section, id, title, imgSrc, isExpanded }} />;
    });
  }

  render() {
    const { title, isExpanded } = this.props;
    return (<div className={styles.sectionContainer}>
      {isExpanded && <p className={styles.sectionTitle}>{title}</p>}
      <ul>
        {this.renderItem(isExpanded)}
      </ul>
    </div>);
  }
}

export default class SideBar extends Component {

  static propTypes = {
    userSection: sectionPropTypes,
    groupSections: sectionsPropTypes,
    companyId: PropTypes.number,
    companyName: PropTypes.string,
    isExpanded: PropTypes.bool,
    onClickToggle: PropTypes.func,
  };

  getBaseStyles = () =>
    `${styles.component} ${this.props.isExpanded ? styles.isExpanded : styles.isCollapsed}`;

  renderSection = (section, { title, items }) =>
    <Section
      key={title}
      {...{ section, title, items, isExpanded: this.props.isExpanded }}
    />;

  renderUserSection = section => this.renderSection('user', section);

  renderGroupSection = section => this.renderSection('group', section);

  render() {
    const { userSection, groupSections, companyId, companyName,
            isExpanded = true, onClickToggle } = this.props;
    const { tab } = explodePath();
    const showUser = tab !== 'timeline';
    const showCompany = companyId && /^(objective|map)$/.test(tab);
    return (
      <div className={this.getBaseStyles()}>
        <div className={styles.header}>
          <img
            className={styles.headerLogo}
            src="/img/skrum_logo.svg"
            alt=""
          />
        </div>
        <div className={styles.toggleArea}>
          <button
            onClick={onClickToggle}
            className={styles.toggleButton}
          >{isExpanded ? '<' : '>'}
          </button>
        </div>
        {showUser && this.renderUserSection(userSection)}
        {groupSections.map(this.renderGroupSection)}
        {showCompany && <SectionItem section="company" id={companyId} title={companyName} isExpanded={isExpanded} />}
      </div>
    );
  }
}
