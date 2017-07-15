import React, { Component } from 'react';
import { connect } from 'react-redux';
import { groupsPropTypes } from './propTypes';
import GroupList from './GroupList';

class GroupListContainer extends Component {

  static propTypes = {
    items: groupsPropTypes,
  };

  render() {
    return (
      <GroupList
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
)(GroupListContainer);
