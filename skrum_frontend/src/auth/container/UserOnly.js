import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import SideBarContainer from '../../navigation/sidebar/SideBarContainer';
import HeaderContainer from '../../navigation/header/HeaderContainer';

const style = {
  layoutBase: {
    display: 'flex',
    width: '100%',
    height: '100vh',
  },
  layoutSide: {
    width: '200px',
  },
  layoutMain: {
    width: '100%',
  },
};

class UserOnly extends Component {
  static propTypes = {
    children: PropTypes.element.isRequired,
  };

  static transfer(props) {
    if (!props.auth.isLoggedIn) {
      browserHistory.push('/login');
    }
  }

  componentWillMount() {
    // UserOnly.transfer(this.props);
  }

  componentWillUpdate() {
    // UserOnly.transfer(nextProps);
  }

  render() {
    return (
      <div style={style.layoutBase}>
        <div style={style.layoutSide}>
          <SideBarContainer />
        </div>
        <main style={style.layoutMain}>
          <HeaderContainer />
          {this.props.children}
        </main>
      </div>);
  }
}

const mapStateToProps = state => state;
export default connect(mapStateToProps)(UserOnly);
