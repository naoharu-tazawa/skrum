import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import SideBarContainer from '../navigation/sidebar/SideBarContainer';
import HeaderContainer from '../navigation/header/HeaderContainer';
import { fetchUserTop } from './action';
import { fetchUserBasics, fetchGroupBasics, fetchCompanyBasics } from '../project/OKR/action';
import { explodePath, isPathFinal } from '../util/RouteUtil';

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
    dispatchFetchUserBasics: PropTypes.func,
    dispatchFetchGroupBasics: PropTypes.func,
    dispatchFetchCompanyBasics: PropTypes.func,
    userId: PropTypes.number,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { userId, dispatchFetchUserInfo, pathname } = this.props;
    dispatchFetchUserInfo(userId);
    if (isPathFinal(pathname)) {
      this.fetchBasics(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchBasics(pathname);
    }
  }

  fetchBasics(pathname) {
    const {
      dispatchFetchUserBasics,
      dispatchFetchGroupBasics,
      dispatchFetchCompanyBasics,
    } = this.props;
    const { section, id, timeframeId } = explodePath(pathname);
    switch (section) {
      case 'user':
        dispatchFetchUserBasics(id, timeframeId);
        break;
      case 'group':
        dispatchFetchGroupBasics(id, timeframeId);
        break;
      case 'company':
        dispatchFetchCompanyBasics(id, timeframeId);
        break;
      default:
        break;
    }
  }

  render() {
    const { pathname } = this.props;
    return (
      <div style={style.layoutBase}>
        <div style={style.layoutSide}>
          <SideBarContainer pathname={pathname} />
        </div>
        <main style={style.layoutMain}>
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
  const dispatchFetchUserInfo = userId =>
    dispatch(fetchUserTop(userId));
  const dispatchFetchUserBasics = (userId, timeframeId) =>
    dispatch(fetchUserBasics(userId, timeframeId));
  const dispatchFetchGroupBasics = (groupId, timeframeId) =>
    dispatch(fetchGroupBasics(groupId, timeframeId));
  const dispatchFetchCompanyBasics = (companyId, timeframeId) =>
    dispatch(fetchCompanyBasics(companyId, timeframeId));
  return {
    dispatchFetchUserInfo,
    dispatchFetchUserBasics,
    dispatchFetchGroupBasics,
    dispatchFetchCompanyBasics,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NavigationContainer);
