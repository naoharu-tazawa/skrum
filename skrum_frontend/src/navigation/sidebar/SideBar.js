import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { sectionPropType, itemsPropTypes, sectionPropTypes, sectionsPropTypes } from './propTypes';
import { explodePath, replacePath } from '../../util/RouteUtil';

const style = {
  /* -----------------------------------
  *  Main
  *  ----------------------------------- */
  sideNav: {
    height: '100vh',
    overflow: 'hidden',
    color: '#fff',
    boxShadow: '0px 0px 10px 0px #000',
    backgroundColor: '#626A7F',
  },
  sideNavTitle: {
    position: 'relative',
    padding: '10px 13px',
    borderBottom: '1px solid #666',
  },
  sideNavTitleText: {
    position: 'absolute',
    top: '14px',
    left: '42px',
    display: 'block',
  },
  isOpen: {
    width: '100%',
  },
  isHide: {
    display: 'none',
  },
  isActive: {
    backgroundColor: 'slategray',
  },
  toggleArea: {
    padding: '10px 20px',
    display: 'flex',
    justifyContent: 'flex-end',
  },
  toggleButton: {
    cursor: 'pointer',
    color: '#fff',
    backgroundColor: 'transparent',
    border: 'none',
    fontSize: '20px',
  },
  userName: {
    padding: '20px',
  },
  companyName: {
    padding: '20px',
  },
  /* -----------------------------------
   *  Header
   *  ----------------------------------- */
  header: {
    backgroundColor: '#27588F',
    padding: '20px',
  },
  /* -----------------------------------
   *  Section
   *  ----------------------------------- */
  sectionContainer: {
    marginBottom: '30px',
  },
  sectionTitle: {
    padding: '5px 20px',
    color: '#818C9A',
  },
  /* -----------------------------------
   *  Section Item
   *  ----------------------------------- */
  sectionItem: {
    padding: '10px 20px',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  sectionItemLink: {
    color: 'inherit',
    width: '100%',
  },
  sectionItemImg: {
    width: '20px',
    height: '20px',
    borderRadius: '50%',
    border: '1px solid #fff',
  },
  sideNavMainItemLink: {
    display: 'block',
    padding: '10px 13px',
    backgroundPosition: '13px center',
    backgroundRepeat: 'no-repeat',
    cursor: 'pointer',
    color: '#fff',
    textDecoration: 'none',
    whiteSpace: 'nowrap',
    marginLeft: '-40px',
  },
  sideNavMenuItemLink: {
    display: 'block',
    padding: '12px 10px 12px', // 50px
    backgroundPosition: '13px center',
    backgroundRepeat: 'no-repeat',
    cursor: 'pointer',
    color: '#fff',
    textDecoration: 'none',
    whiteSpace: 'nowrap',
  },
  sideNavMenuItemLinkHover: {
    ':hover': {
      backgroundColor: '#38b6a5',
    },
  },
  sideNavMenuItemNotice: {
    display: 'inline-block',
    position: 'relative',
    top: '-8px',
    width: '6px',
    height: '6px',
    marginLeft: '5px',
    backgroundColor: '#f66c54',
    borderRadius: '50%',
  },
};

class SectionItem extends Component {
  static propTypes = {
    section: sectionPropType.isRequired,
    id: PropTypes.number.isRequired,
    title: PropTypes.string.isRequired,
    imgSrc: PropTypes.string,
  };

  getStyle() {
    const { section, id } = this.props;
    const { section: currentSection, id: currentId } = explodePath();
    const isActive = section === currentSection && `${id}` === currentId;
    return { ...style.sectionItem, ...(isActive ? style.isActive : {}) };
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
      style={style.sectionItemImg}
    />);
  }

  render() {
    const { title } = this.props;
    return (<li style={this.getStyle()}>
      <Link to={this.getPath()} style={style.sectionItemLink}>
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
    return (<div style={style.sectionContainer}>
      <p style={style.sectionTitle}>{this.props.title}</p>
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

  getBaseStyle = () => (
    this.props.isOpen
      ? { ...style.sideNav, ...style.isOpen }
      : style.sideNav);

  renderSection = (section, { title, items }) => (
    <Section
      key={title}
      {...{ section, title, items }}
    />);

  renderUserSection = section => this.renderSection('user', section);

  renderGroupSection = section => this.renderSection('group', section);

  render() {
    const { userSection, groupSections, companyId, companyName, onClickToggle } = this.props;
    return (
      <div style={this.getBaseStyle()}>
        <div style={style.header}>Skrum</div>
        <div style={style.toggleArea}>
          <button
            onClick={onClickToggle}
            style={style.toggleButton}
          >≡
          </button>
        </div>
        {this.renderUserSection(userSection)}
        {groupSections.map(this.renderGroupSection)}
        {companyId ? <SectionItem section="company" id={companyId} title={companyName} /> : null}
      </div>
    );
  }
}
