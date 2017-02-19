import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import SideBar from '../../common/component/SideBar';
import { logout } from '../../auth/action';

const defaultWrapperStyle = {
  display: 'table',
  width: '100%',
  height: '100vh',
};

const mainStyle = {
  display: 'table-cell',
};

const sideBarStyle = {
  display: 'table-cell',
  width: '15%',
  verticalAlign: 'top',
};

class App extends Component {
  static propTypes = {
    children: PropTypes.element,
    auth: PropTypes.shape({
      isLoggedIn: PropTypes.bool.isRequired,
    }),
    handleLogout: PropTypes.func,
    location: PropTypes.shape({
      current: PropTypes.string,
      subMenus: PropTypes.arrayOf(PropTypes.object.isRequired),
    }),
  };
  static defaultProps = {
    location: {
      current: null,
      subMenus: [],
    },
  };
  defaultItems = [
    { title: 'menu1', urlOrFunc: '/piyo', category: 'any' },
    { title: 'menu2', urlOrFunc: '/hoge', category: 'some' },
    { title: 'menu3', urlOrFunc: this.props.handleLogout },
  ];
  renderAuthButton() {
    if (!this.props.auth.isLoggedIn) {
      return (<Link className="mdl-navigation__link" to="/login">ログイン</Link>);
    }
    return (<a // eslint-disable-line
      href="#"
      className="mdl-navigation__link"
      onClick={() => this.props.handleLogout()}
    >
      ログアウト
    </a>);
  }

  renderTab() {
    const { current, subMenus } = this.props.location;
    const className = (menu) => {
      let name = 'mdl-layout__tab';
      if (menu.isActive) {
        name += ' is-active';
      }

      return name;
    };
    /* eslint-disable jsx-a11y/href-no-hash, consistent-return */
    if (!subMenus) return;
    return subMenus.map((menu, index) => (<Link
      key={`MENU_${index}`}
      to={current + menu.path}
      className={className(menu)}
    >{menu.title}</Link>));
  }

  renderSideBar() {
    const isLoggedIn = this.props.auth.isLoggedIn;
    if (isLoggedIn) {
      return (<nav
        style={sideBarStyle}
      >
        <SideBar barItems={this.defaultItems} />
      </nav>);
    }
  }

  render() {
    return (
      <div style={defaultWrapperStyle}>
        {this.renderSideBar()}
        <main style={mainStyle}>
          { this.props.children }
        </main>
      </div>
    );
  }
}

const mapStateToProps = (state) => {
  const { auth, location } = state;
  return { auth, location };
};

const mapDispatchToProps = (dispatch) => {
  const handleLogout = () => dispatch(logout());
  return { handleLogout };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);
