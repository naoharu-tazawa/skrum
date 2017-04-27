import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import NavigationContainer from '../../navigation/NavigationContainer';

class Authenticated extends Component {
  static propTypes = {
    login: PropTypes.string.isRequired,
    isAuthorized: PropTypes.bool,
    defaultId: PropTypes.number,
    children: PropTypes.element,
  };

  componentWillMount() {
    this.transfer();
  }

  componentWillReceiveProps(next) {
    this.transfer(next);
  }

  transfer(props = this.props) {
    const { login, isAuthorized, defaultId } = props;
    if (!isAuthorized) {
      browserHistory.push(login);
    }
    const to = id => `/team/${id}/okr`;
    const current = window.location.pathname;
    if (defaultId && current === '/team') {
      browserHistory.push(to(defaultId));
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
  const { isAuthorized } = state.auth;
  const { data } = state.user || {};
  const { departments = [] } = data || {};
  const { group_id } = departments[0] || {};
  return { isAuthorized, defaultId: group_id };
};

export default connect(mapStateToProps)(Authenticated);