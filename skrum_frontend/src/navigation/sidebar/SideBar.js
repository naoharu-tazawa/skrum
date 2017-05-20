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
  };

  getStyles() {
    const { section, id } = this.props;
    const { section: currentSection, id: currentId } = explodePath();
    const isActive = section === currentSection && `${id}` === currentId;
    return `${styles.sectionItem} ${isActive ? styles.isActive : ''}`;
  }

  getPath() {
    const { section, id } = this.props;
    // if (/^(user|group|company)$/.test(section)) {
    return replacePath({ section, id });
    // }
  }

  renderImg() {
    const { imgSrc } = this.props;
    if (!imgSrc) return;
    return (<img
      src={imgSrc}
      alt=""
      className={styles.sectionItemImg}
    />);
  }

  render() {
    const { title } = this.props;
    return (<li className={this.getStyles()}>
      <Link to={this.getPath()} className={styles.sectionItemLink}>
        {title}
      </Link>
      {this.renderImg()}
    </li>);
  }
}

class Section extends Component {
  static propTypes = {
    section: sectionPropType.isRequired,
    title: PropTypes.string.isRequired,
    items: itemsPropTypes.isRequired,
  };

  renderItem() {
    const { section } = this.props;
    return this.props.items.map((item) => {
      const { title, imgSrc, id } = item;
      return (
        <SectionItem
          key={id}
          section={section}
          id={id}
          title={title}
          imgSrc={imgSrc}
        />);
    });
  }

  render() {
    return (<div className={styles.sectionContainer}>
      <p className={styles.sectionTitle}>{this.props.title}</p>
      <ul>
        {this.renderItem()}
      </ul>
    </div>);
  }
}

export default class SideBar extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    onClickToggle: PropTypes.func,
    userSection: sectionPropTypes,
    groupSections: sectionsPropTypes,
    companyId: PropTypes.number,
    companyName: PropTypes.string,
  };

  static defaultProps = {
    isOpen: true,
  };

  getBaseStyles = () =>
    `${styles.component} ${this.props.isOpen ? styles.isOpen : ''}`;

  renderSection = (section, { title, items }) => (
    <Section
      key={title}
      {...{ section, title, items }}
    />);

  renderUserSection = section => this.renderSection('user', section);

  renderGroupSection = section => this.renderSection('group', section);

  render() {
    const { userSection, groupSections, companyId, companyName, onClickToggle } = this.props;
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
          >≡
          </button>
        </div>
        {showUser ? this.renderUserSection(userSection) : null}
        {groupSections.map(this.renderGroupSection)}
        {showCompany ? <SectionItem section="company" id={companyId} title={companyName} /> : null}
      </div>
    );
  }
}
