import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import style from './MenuBar.css';

/*
const style = {
  menuNav: {
    display: 'flex',
    height: '100vh',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
    height: '60px',
    width: '100%',
  },
  menuNavMenu: {
    margin: '30px auto',
  },
  menuNavTitle: {
    position: 'relative',
    padding: '10px 13px',
  },
  menuNavTitleText: {
    position: 'absolute',
    top: '14px',
    left: '42px',
    display: 'block',
  },
  isHide: {
    display: 'none',
  },
  menuNavUserName: {
    margin: 'auto 0',
  },
  menuNavUserImage: {
    display: 'block',
    minWidth: '36px',
    minHeight: '36px',
    margin: 'auto 0 auto 1em',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  menuNavMenuItemLink: {
//    display: 'block',
    padding: '12px 10px 12px', // 50px
    backgroundPosition: '13px center',
    backgroundRepeat: 'no-repeat',
    cursor: 'pointer',
    color: '#fff',
    textDecoration: 'none',
    whiteSpace: 'nowrap',
  },
  menuNavMenuItemTask: {
    backgroundImage: `url(${imgSrc('common/ic_task.svg')})`,
  },
  menuNavMenuItemMessage: {
    backgroundImage: `url(${imgSrc('common/ic_message.svg')})`,
  },
  menuNavMenuItemCandidate: {
    backgroundImage: `url(${imgSrc('common/ic_candidate.svg')})`,
  },
  isActive: {
    backgroundColor: '#38b6a5',
  },
  menuNavMenuItemLinkHover: {
    ':hover': {
      backgroundColor: '#38b6a5',
    },
  },
  menuNavMenuItemNotice: {
    display: 'inline-block',
    position: 'relative',
    top: '-8px',
    width: '6px',
    height: '6px',
    marginLeft: '5px',
    backgroundColor: '#f66c54',
    borderRadius: '50%',
  },
  dropdown: {
    backgroundImage: 'linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%)',
    backgroundPosition: 'calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px), calc(100% - 2.5em) 0.5em',
    backgroundSize: '5px 5px, 5px 5px, 1px 1.5em',
    backgroundRepeat: 'no-repeat',
    width: '40px',
    marginTop: '10px',
  }
};
*/

class MenuBar extends Component {

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
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  static createNavItemStyle(isActive, category) {
    const navItemStyle = Object.assign({}, style.menuNavMenuItemLink);

    if (isActive) {
      Object.assign(navItemStyle, style.isActive);
    }

    switch (category) {
      case 'task':
        Object.assign(navItemStyle, style.menuNavMenuItemTask);
        break;
      case 'message':
        Object.assign(navItemStyle, style.menuNavMenuItemMessage);
        break;
      case 'candidate':
        Object.assign(navItemStyle, style.menuNavMenuItemCandidate);
        break;
      default:
    }
    return navItemStyle;
  }

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.menuNav, style.isOpen));
    }
    return style.menuNav;
  }

  static renderMenuNaviTitle(isOpen) {
    if (isOpen) {
      return (<pre>Skrum</pre>);
    }
  }

  static renderNotice(isNew) {
    if (isNew) {
      return (<span style={style.menuNavMenuItemNotice} />);
    }
  }

  static renderBarItem(barItem, index, isFunc) {
    let linkProps
    if (isFunc) {
      linkProps = { to: barItem.urlOrFunc }
    } else {
      linkProps = { onClick: barItem.urlOrFunc }
    }
    return (
      <Link
        key={index}
        style={MenuBar.createNavItemStyle(barItem.isActive, barItem.category)}
        className={cssClass(style.menuNavMenuItemLinkHover)}
        {...linkProps}
      >
        {barItem.title}
        {MenuBar.renderNotice(barItem.isNew)}
      </Link>
    )
  }

  renderBarItems() {
    return this.props.barItems.map((barItem, index) => {
      const isFunc = typeof barItem.urlOrFunc === 'function';
      return MenuBar.renderBarItem(barItem, index, isFunc);
    });
  }

  render() {
    return (
      <div style={MenuBar.checkNaviOpen(this.props.isOpen)}>
        <div style={style.menuNavTitle}>
          {MenuBar.renderMenuNaviTitle(this.props.isOpen)}
        </div>
        <div style={style.menuNavMenu}>
          {this.renderBarItems()}
        </div>
        <div style={style.menuNavUserName}>
          <div>{this.props.user.name}</div>
        </div>
        <div style={style.menuNavUserImage} />
        <div style={style.dropdown} />
      </div>
    );
  }
}

export default MenuBar;
