import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import SideBarContainer from '../navigation/sidebar/SideBarContainer';
import HeaderContainer from '../navigation/header/HeaderContainer';
import styles from './NavigationContainer.css';
import { fetchUserTop } from './action';

class NavigationContainer extends Component {

  static propTypes = {
    children: PropTypes.oneOfType([
      PropTypes.element,
      PropTypes.arrayOf([PropTypes.element]),
    ]),
    dispatchFetchUserInfo: PropTypes.func,
    userId: PropTypes.number,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { dispatchFetchUserInfo, userId } = this.props;
    dispatchFetchUserInfo(userId);
  }

  render() {
    const { pathname } = this.props;
    return (
      <div className={styles.layoutBase}>
        <div className={styles.layoutSide}>
          <SideBarContainer pathname={pathname} />
        </div>
        <main className={styles.layoutMain}>
          <HeaderContainer pathname={pathname} />
          {this.props.children}
        </main>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { userId } = state.auth;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { state, userId, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserInfo = userId => dispatch(fetchUserTop(userId));
  return {
    dispatchFetchUserInfo,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NavigationContainer);
