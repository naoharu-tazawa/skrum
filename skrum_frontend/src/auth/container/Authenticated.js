import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import { find, mapValues, values, some } from 'lodash';
import NavigationContainer from '../../navigation/NavigationContainer';
import { isPathFinal, explodePath, implodePath } from '../../util/RouteUtil';
import { logout } from '../action';

class Authenticated extends Component {
  static propTypes = {
    top: PropTypes.string.isRequired,
    login: PropTypes.string.isRequired,
    toRelogin: PropTypes.bool.isRequired,
    pathname: PropTypes.string,
    isAuthorized: PropTypes.bool,
    currentUserId: PropTypes.number,
    timeframeId: PropTypes.number,
    children: PropTypes.element,
    dispatchForceLogout: PropTypes.func.isRequired,
  };

  componentWillMount() {
    this.transfer();
  }

  componentWillReceiveProps(next) {
    this.transfer(next);
  }

  transfer(props = this.props) {
    const { login, pathname, isAuthorized, currentUserId, timeframeId, toRelogin,
      dispatchForceLogout } = props;
    if (!isAuthorized) {
      browserHistory.push(login);
    } else if (toRelogin) {
      dispatchForceLogout();
    } else if (currentUserId && timeframeId && !isPathFinal(pathname)) {
      const { tab = 'objective', subject = 'user', id = currentUserId } = explodePath(pathname);
      browserHistory.replace(implodePath({ tab, subject, id, timeframeId }));
    }
  }

  render() {
    const { isAuthorized, top, children } = this.props;
    return !isAuthorized ? null : (
      <NavigationContainer top={top}>
        {children}
      </NavigationContainer>);
  }
}

const mapStateToProps = (state) => {
  const { isAuthorized, userId: currentUserId } = state.auth;
  const { timeframes = [] } = state.top.data || {};
  const { timeframeId } = find(timeframes, { defaultFlg: 1 }) || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const toRelogin = some(values(mapValues(state, 'error')), ['message', 'ログインしてください。']);
  return { toRelogin, pathname, isAuthorized, currentUserId, timeframeId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchForceLogout = () => dispatch(logout());
  return {
    dispatchForceLogout,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Authenticated);
