import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import SideBarContainer from '../navigation/sidebar/SideBarContainer';
import HeaderContainer from '../navigation/header/HeaderContainer';
import { fetchUserTop } from './action';

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
    display: 'flex',
    flexDirection: 'column',
    width: '100%',
    overflowY: 'hidden',
  },
};

class NavigationContainer extends Component {

  static propTypes = {
    children: PropTypes.oneOfType([
      PropTypes.element,
      PropTypes.arrayOf([PropTypes.element]),
    ]),
    dispatchFetchUserInfo: PropTypes.func,
  };

  static defaultProps = {
  };

  componentWillMount() {
    this.props.dispatchFetchUserInfo();
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

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserInfo = userId => dispatch(fetchUserTop(userId));
  return { dispatchFetchUserInfo };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NavigationContainer);
