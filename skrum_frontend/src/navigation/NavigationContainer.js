import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { trim } from 'lodash';
import CompanySetupContainer from './setup/CompanySetupContainer';
import UserSetupContainer from './setup/UserSetupContainer';
import SideBarContainer from './sidebar/SideBarContainer';
import HeaderContainer from './header/HeaderContainer';
import styles from './NavigationContainer.css';
import { fetchUserTop } from './action';
import { logout } from '../auth/action';

class NavigationContainer extends Component {

  static propTypes = {
    top: PropTypes.string.isRequired,
    children: PropTypes.node,
    pathname: PropTypes.string.isRequired,
    currentUserId: PropTypes.number.isRequired,
    roleLevel: PropTypes.number.isRequired,
    isFetching: PropTypes.bool,
    companyName: PropTypes.string,
    userName: PropTypes.string,
    dispatchFetchUserInfo: PropTypes.func.isRequired,
    dispatchForceLogout: PropTypes.func.isRequired,
  };

  componentWillMount() {
    const { dispatchFetchUserInfo, currentUserId, dispatchForceLogout } = this.props;
    dispatchFetchUserInfo(currentUserId)
      .then(({ error } = {}) => error && dispatchForceLogout());
  }

  render() {
    const { top, pathname, currentUserId, roleLevel, isFetching,
      companyName, userName } = this.props;
    if (isFetching) return null;
    return (
      <div className={styles.layoutBase}>
        {!companyName && <CompanySetupContainer />}
        {companyName && !userName && <UserSetupContainer />}
        {userName && <SideBarContainer {...{ top, pathname, currentUserId, roleLevel }} />}
        {userName && <main className={styles.layoutMain}>
          <HeaderContainer {...{ pathname, currentUserId, roleLevel }} />
          {this.props.children}
        </main>}
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { userId: currentUserId, roleLevel } = state.auth;
  const { isFetching, data = {} } = state.top || {};
  const { company = {}, users = [] } = data;
  const { name: companyName } = company;
  const [{ name: userName } = {}] = users;
  return { pathname, currentUserId, roleLevel, isFetching, companyName, userName: trim(userName) };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserInfo = userId => dispatch(fetchUserTop(userId));
  const dispatchForceLogout = () => dispatch(logout());
  return {
    dispatchFetchUserInfo,
    dispatchForceLogout,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NavigationContainer);
