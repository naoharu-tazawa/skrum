import React, { Component } from 'react';
import PropTypes from 'prop-types';

const style = {
  /* -----------------------------------
  *  Main
  *  ----------------------------------- */
  sideNav: {
    height: '100vh',
    overflow: 'hidden',
    color: '#fff',
    boxShadow: '0px 0px 10px 0px #000',
    backgroundColor: '#626A7E',
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
    backgroundColor: '#325C8A',
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
    title: PropTypes.string.isRequired,
    imgSrc: PropTypes.string,
  };

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
    return (<li style={style.sectionItem}>
      <div>
        {this.props.title}
      </div>
      {this.renderImg()}
    </li>);
  }
}

class Section extends Component {
  static propTypes = {
    title: PropTypes.string.isRequired,
    items: PropTypes.arrayOf(PropTypes.shape({
      title: PropTypes.string.isRequired,
      imgSrc: PropTypes.string,
    })).isRequired,
  };

  renderItem() {
    return this.props.items.map((item) => {
      const { title, imgSrc } = item;
      return (<SectionItem title={title} imgSrc={imgSrc} />);
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
    isOpen: PropTypes.bool.isRequired,
    onClickToggle: PropTypes.func.isRequired,
    userName: PropTypes.string.isRequired,
    companyName: PropTypes.string.isRequired,
    sections: PropTypes.arrayOf(PropTypes.shape({
      title: PropTypes.string.isRequired,
      items: PropTypes.arrayOf(PropTypes.shape({
        title: PropTypes.string.isRequired,
        imgSrc: PropTypes.string.isRequired,
      })).isRequired,
    })).isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  getBaseStyle() {
    if (this.props.isOpen) {
      return (Object.assign({}, style.sideNav, style.isOpen));
    }
    return style.sideNav;
  }

  renderContent() {
    return this.props.sections.map((section) => {
      const { title, items } = section;
      return (<Section
        title={title}
        items={items}
      />);
    });
  }

  render() {
    return (
      <div style={this.getBaseStyle()}>
        <div style={style.header}>Skrum</div>
        <div style={style.toggleArea}>
          <button
            onClick={this.props.onClickToggle}
            style={style.toggleButton}
          >≡
          </button>
        </div>
        <p style={style.userName}>{this.props.userName}</p>
        {this.renderContent()}
        <p style={style.companyName}>{this.props.companyName}</p>
      </div>
    );
  }
}
