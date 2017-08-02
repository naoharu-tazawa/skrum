import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberList from './GroupMemberList';
import { addGroupMember, deleteGroupMember } from '../action';

class GroupMemberListContainer extends Component {

  static propTypes = {
    groupId: PropTypes.number,
    groupName: PropTypes.string,
    items: groupMembersPropTypes,
    roleLevel: PropTypes.number.isRequired,
    dispatchAddGroupMember: PropTypes.func.isRequired,
    dispatchDeleteGroupMember: PropTypes.func.isRequired,
  };

  render() {
    const { groupId, groupName, items, roleLevel,
      dispatchAddGroupMember, dispatchDeleteGroupMember } = this.props;
    return !groupId ? null : (
      <GroupMemberList
        {...{
          groupId,
          groupName,
          items,
          roleLevel,
          dispatchAddGroupMember,
          dispatchDeleteGroupMember,
        }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { roleLevel } = state.auth || {};
  const { group, members = [] } = state.groupManagement.group || {};
  const { groupId, name: groupName } = group || {};
  const items = members.map((member) => {
    const { userId, name, position, achievementRate, lastLogin } = member;
    return {
      id: userId,
      name,
      position,
      achievementRate,
      lastLogin,
    };
  });
  return { groupId, groupName, roleLevel, items };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchAddGroupMember = (timeframeId, groupId, userId) =>
    dispatch(addGroupMember(timeframeId, groupId, userId));
  const dispatchDeleteGroupMember = (groupId, userId) =>
    dispatch(deleteGroupMember(groupId, userId));
  return { dispatchAddGroupMember, dispatchDeleteGroupMember };
};

const mergeProps = (state, { dispatchAddGroupMember, dispatchDeleteGroupMember }, props) => ({
  ...state,
  ...props,
  dispatchAddGroupMember: (groupId, userId) =>
    dispatchAddGroupMember(props.timeframeId, groupId, userId),
  dispatchDeleteGroupMember,
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(GroupMemberListContainer);
