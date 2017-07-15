import React, { Component } from 'react';
import { connect } from 'react-redux';
import { usersPropTypes } from './propTypes';
import UserList from './UserList';

class UserListContainer extends Component {

  static propTypes = {
    items: usersPropTypes,
  };

  render() {
    return (
      <UserList
        items={this.props.items}
      />);
  }
}

const mapStateToProps = (state) => {
  const { users = [] } = state.userSetting || {};
  const items = users.map((user) => {
    const { userId, name, roleAssignmentId, roleAssignmentName, lastLogin } = user;
    return {
      id: userId,
      name,
      roleAssignmentId,
      roleAssignmentName,
      lastLogin,
    };
  });
  return { items };
};

export default connect(
  mapStateToProps,
)(UserListContainer);
