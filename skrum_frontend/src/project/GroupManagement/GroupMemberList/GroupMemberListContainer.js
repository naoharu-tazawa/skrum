import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { toNumber } from 'lodash';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberList from './GroupMemberList';
import { addGroupMember, deleteGroupMember } from '../action';
import { syncJoinGroup, syncLeaveGroup } from '../../../navigation/action';

class GroupMemberListContainer extends Component {

  static propTypes = {
    groupId: PropTypes.number,
    groupName: PropTypes.string,
    members: groupMembersPropTypes,
    roleLevel: PropTypes.number.isRequired,
    dispatchAddGroupMember: PropTypes.func.isRequired,
    dispatchDeleteGroupMember: PropTypes.func.isRequired,
  };

  render() {
    const { groupId, groupName, members, roleLevel,
      dispatchAddGroupMember, dispatchDeleteGroupMember } = this.props;
    return !groupId ? null : (
      <GroupMemberList
        {...{
          groupId,
          groupName,
          members,
          roleLevel,
          dispatchAddGroupMember,
          dispatchDeleteGroupMember,
        }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { userId: currentUserId, roleLevel } = state.auth || {};
  const { group, members: items = [] } = state.groupManagement.group || {};
  const { groupId, name: groupName } = group || {};
  const members = items.map(({ userId, name, position, achievementRate, lastLogin }) => ({
    id: userId,
    name,
    position,
    achievementRate: toNumber(achievementRate),
    lastLogin,
  }));
  return { groupId, groupName, currentUserId, roleLevel, members };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchAddGroupMember = (timeframeId, groupId, userId) =>
    dispatch(addGroupMember(timeframeId, groupId, userId));
  const dispatchDeleteGroupMember = (groupId, userId) =>
    dispatch(deleteGroupMember(groupId, userId));
  const dispatchSyncJoinGroup = result => dispatch(syncJoinGroup(result));
  const dispatchSyncLeaveGroup = result => dispatch(syncLeaveGroup(result));
  return {
    dispatchAddGroupMember,
    dispatchDeleteGroupMember,
    dispatchSyncJoinGroup,
    dispatchSyncLeaveGroup,
  };
};

const mergeProps = ({ currentUserId, ...state }, {
  dispatchAddGroupMember,
  dispatchDeleteGroupMember,
  dispatchSyncJoinGroup,
  dispatchSyncLeaveGroup,
}, { timeframeId, ...props }) => ({
  ...state,
  ...props,
  dispatchAddGroupMember: (groupId, userId) =>
    dispatchAddGroupMember(timeframeId, groupId, userId)
      .then((result) => {
        if (userId === currentUserId) dispatchSyncJoinGroup(result);
        return result;
      }),
  dispatchDeleteGroupMember: (groupId, userId) =>
    dispatchDeleteGroupMember(groupId, userId)
      .then((result) => {
        if (userId === currentUserId) dispatchSyncLeaveGroup(result);
        return result;
      }),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(GroupMemberListContainer);
