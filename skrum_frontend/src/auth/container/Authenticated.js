import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import _ from 'lodash';
import NavigationContainer from '../../navigation/NavigationContainer';
import { implodePath, isPathFinal } from '../../util/RouteUtil';

class Authenticated extends Component {
  static propTypes = {
    login: PropTypes.string.isRequired,
    isAuthorized: PropTypes.bool,
    userId: PropTypes.number,
    timeframeId: PropTypes.number,
    children: PropTypes.element,
  };

  componentWillMount() {
    this.transfer();
  }

  componentWillReceiveProps(next) {
    this.transfer(next);
  }

  transfer(props = this.props) {
    const { login, isAuthorized, userId, timeframeId } = props;
    if (!isAuthorized) {
      browserHistory.push(login);
    } else if (userId && timeframeId && !isPathFinal(window.location.pathname)) {
      browserHistory.push(implodePath({ subject: 'user', id: userId, timeframeId, tab: 'objective' }));
    }
  }

  render() {
    return (
      <NavigationContainer>
        {this.props.children}
      </NavigationContainer>);
  }
}

const mapStateToProps = (state) => {
  const { isAuthorized, userId } = state.auth;
  const { timeframes = [] } = state.top.data || {};
  const timeframeId = (_.find(timeframes, { defaultFlg: 1 }) || {}).timeframeId;
  return { isAuthorized, userId, timeframeId };
};

export default connect(
  mapStateToProps,
)(Authenticated);
