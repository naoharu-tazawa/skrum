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
    pathname: PropTypes.string.isRequired,
    currentUserId: PropTypes.number.isRequired,
    roleLevel: PropTypes.number.isRequired,
    dispatchFetchUserInfo: PropTypes.func.isRequired,
  };

  componentWillMount() {
    const { dispatchFetchUserInfo, currentUserId } = this.props;
    dispatchFetchUserInfo(currentUserId);
  }

  render() {
    const { pathname, currentUserId, roleLevel } = this.props;
    return (
      <div className={styles.layoutBase}>
        <SideBarContainer {...{ pathname, currentUserId, roleLevel }} />
        <main className={styles.layoutMain}>
          <HeaderContainer {...{ pathname, currentUserId, roleLevel }} />
          {this.props.children}
        </main>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { userId: currentUserId, roleLevel } = state.auth;
  return { state, pathname, currentUserId, roleLevel };
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
