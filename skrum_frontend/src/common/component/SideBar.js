import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';

const style = {
  sideNav: {
    height: '100vh',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
    //width: '100%',
  },
  sideNavMenu: {
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
  sideNavAdviser: {
    padding: '12px',
    borderBottom: '1px solid #fff',
    boxSizing: 'border-box',
  },
  isHide: {
    display: 'none',
  },
  sideNavAdviserImage: {
    display: 'inline-block',
    minWidth: '36px',
    minHeight: '36px',
    marginRight: '5px',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  sideNavAdviserName: {
    display: 'inline-block',
    width: '75%',
    paddingTop: '3px',
    verticalAlign: 'top',
  },
  sideNavAdviserTitle: {
    display: 'block',
    marginTop: '5px',
    fontSize: '13px',
  },
  sideNavMenuItem: {
//    borderBottom: '1px solid #666',
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
  sideNavMenuItemTask: {
    backgroundImage: `url(${imgSrc('common/ic_task.svg')})`,
  },
  sideNavMenuItemMessage: {
    backgroundImage: `url(${imgSrc('common/ic_message.svg')})`,
  },
  sideNavMenuItemCandidate: {
    backgroundImage: `url(${imgSrc('common/ic_candidate.svg')})`,
  },
  isActive: {
    backgroundColor: '#38b6a5',
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

class SideBar extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    barItems: PropTypes.arrayOf(
      PropTypes.shape({
        title: PropTypes.string,
        urlOrFunc: PropTypes.oneOfType([PropTypes.string, PropTypes.func]),
        category: PropTypes.string,
        isActive: PropTypes.bool,
        isNew: PropTypes.bool,
      }),
    ),
  };

  static defaultProps = {
    isOpen: true,
  };

  static createNavItemStyle(isActive, category) {
    const navItemStyle = Object.assign({}, (category === 'main') ? style.sideNavMainItemLink : style.sideNavMenuItemLink);

    if (isActive) {
      Object.assign(navItemStyle, style.isActive);
    }

    switch (category) {
      case 'task':
        Object.assign(navItemStyle, style.sideNavMenuItemTask);
        break;
      case 'message':
        Object.assign(navItemStyle, style.sideNavMenuItemMessage);
        break;
      case 'candidate':
        Object.assign(navItemStyle, style.sideNavMenuItemCandidate);
        break;
      default:
    }
    return navItemStyle;
  }

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.sideNav, style.isOpen));
    }
    return style.sideNav;
  }

  static checkShowAdviser(isOpen) {
    if (isOpen) {
      return style.sideNavAdviser;
    }
    return (Object.assign({}, style.sideNavAdviser, style.isHide));
  }

  static renderSideNaviTitle(isOpen) {
    if (isOpen) {
      return (<pre>SKRUM</pre>);
    }
  }

  static renderNotice(isNew) {
    if (isNew) {
      return (<span style={style.sideNavMenuItemNotice} />);
    }
  }

  static renderBarItem(barItem, index, isFunc) {
    let linkProps
    if (isFunc) {
      linkProps = { to: barItem.urlOrFunc }
    } else {
      linkProps = { onClick: barItem.urlOrFunc }
    }
    const link = (
      <Link
        key={index}
        style={SideBar.createNavItemStyle(barItem.isActive, barItem.category)}
        className={cssClass(style.sideNavMenuItemLinkHover)}
        {...linkProps}
      >
        {barItem.title}
        {SideBar.renderNotice(barItem.isNew)}
      </Link>
    )
    if (barItem.category === 'main') {
      return link
    }
    return (
      <li
        style={style.sideNavMenuItem}
        key={index}
      >
        {link}
      </li>
    );
  }

  renderBarItems() {
    return this.props.barItems.map((barItem, index) => {
      const isFunc = typeof barItem.urlOrFunc === 'function';
      return SideBar.renderBarItem(barItem, index, isFunc);
    });
  }

  render() {
    return (
      <div style={SideBar.checkNaviOpen(this.props.isOpen)}>
        <ul style={style.sideNavMenu}>
          {this.renderBarItems()}
        </ul>
      </div>
    );
  }
}

export default SideBar;
