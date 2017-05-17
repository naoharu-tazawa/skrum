import React, { Component } from 'react';
import { connect } from 'react-redux';
import { userGroupsPropTypes } from './propTypes';
import UserGroupList from './UserGroupList';

class UserGroupListContainer extends Component {

  static propTypes = {
    items: userGroupsPropTypes,
  };

  render() {
    return (
      <UserGroupList
        items={this.props.items}
      />);
  }
}

const mapStateToProps = (state) => {
  const { groups = [] } = state.groupManagement.user || {};
  const items = groups.map((group) => {
    const { groupId, groupName, achievementRate } = group;
    return {
      id: groupId,
      name: groupName,
      achievementRate,
    };
  });
  return { items };
};

export default connect(
  mapStateToProps,
)(UserGroupListContainer);
