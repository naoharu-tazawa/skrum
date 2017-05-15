import React, { Component } from 'react';
import { connect } from 'react-redux';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberList from './GroupMemberList';

class GroupMemberListContainer extends Component {

  static propTypes = {
    items: groupMembersPropTypes.isRequired,
  };

  render() {
    return (
      <GroupMemberList
        items={this.props.items}
      />);
  }
}

const mapStateToProps = (state) => {
  const { members = [] } = state.groupManagement.group || {};
  const items = members.map((member) => {
    const { userId, name, position, lastLogin } = member;
    return {
      id: userId,
      name,
      position,
      lastLogin,
    };
  });
  return { items };
};

export default connect(
  mapStateToProps,
)(GroupMemberListContainer);
