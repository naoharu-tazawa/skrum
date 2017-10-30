import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { userPropTypes } from './propTypes';
import UserInfo from './UserInfo';

class UserInfoContainer extends Component {

  static propTypes = {
    user: userPropTypes,
    currentUserId: PropTypes.number,
  };

  render() {
    const { user, currentUserId } = this.props;
    return !user ? null : (
      <UserInfo {...{ user, currentUserId }} />);
  }
}

const mapStateToProps = (state) => {
  const { userId: currentUserId } = state.auth || {};
  const { basics = {} } = state;
  const { isFetching, user = {} } = basics;
  return isFetching ? {} : { user: user.user, currentUserId };
};

export default connect(
  mapStateToProps,
)(UserInfoContainer);
