import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { usersPropTypes } from './propTypes';
import { fetchUsers, resetPassword, assignRole, deleteUser } from '../action';
import UserList from './UserList';

class UserListContainer extends Component {

  static propTypes = {
    isFetchingUsers: PropTypes.bool.isRequired,
    count: PropTypes.number.isRequired,
    users: usersPropTypes.isRequired,
    currentUserId: PropTypes.number.isRequired,
    currentRoleLevel: PropTypes.number.isRequired,
    dispatchFetchUsers: PropTypes.func.isRequired,
    dispatchResetPassword: PropTypes.func.isRequired,
    dispatchAssignRole: PropTypes.func.isRequired,
    dispatchDeleteUser: PropTypes.func.isRequired,
  };

  render() {
    const { isFetchingUsers, count, users, currentUserId, currentRoleLevel, dispatchFetchUsers,
      dispatchResetPassword, dispatchAssignRole, dispatchDeleteUser } = this.props;
    return (
      <UserList
        {...{ isFetchingUsers, count, users, currentUserId, currentRoleLevel, dispatchFetchUsers }}
        {...{ dispatchResetPassword, dispatchAssignRole, dispatchDeleteUser }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { userId: currentUserId, roleLevel: currentRoleLevel } = state.auth || {};
  const { isFetchingUsers, count, users: items = [] } = state.userSetting || {};
  const users = items.map(({ userId, userName, roleAssignmentId, roleLevel, lastLogin }) => ({
    id: userId,
    name: userName,
    roleAssignmentId,
    roleLevel,
    lastLogin,
  }));
  return { isFetchingUsers, count, users, currentUserId, currentRoleLevel };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUsers = (keyword, pageNo) =>
    dispatch(fetchUsers(keyword, pageNo));
  const dispatchResetPassword = id =>
    dispatch(resetPassword(id));
  const dispatchAssignRole = (id, roleAssignmentId) =>
    dispatch(assignRole(id, roleAssignmentId));
  const dispatchDeleteUser = id =>
    dispatch(deleteUser(id));
  return {
    dispatchFetchUsers,
    dispatchResetPassword,
    dispatchAssignRole,
    dispatchDeleteUser,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserListContainer);
