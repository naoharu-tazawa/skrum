import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { userGroupsPropTypes } from './propTypes';
import UserGroupList from './UserGroupList';
import { joinGroup, leaveGroup } from '../action';
import { syncJoinGroup, syncLeaveGroup } from '../../../navigation/action';

class UserGroupListContainer extends Component {

  static propTypes = {
    userId: PropTypes.number,
    userName: PropTypes.string,
    items: userGroupsPropTypes,
    dispatchJoinGroup: PropTypes.func.isRequired,
    dispatchLeaveGroup: PropTypes.func.isRequired,
  };

  render() {
    const { userId, userName, items,
      dispatchJoinGroup, dispatchLeaveGroup } = this.props;
    return !userId ? null : (
      <UserGroupList
        {...{ userId, userName, items, dispatchJoinGroup, dispatchLeaveGroup }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { user, groups = [] } = state.groupManagement.user || {};
  const { userId, lastName, firstName } = user || {};
  const items = groups.map((group) => {
    const { groupId, groupName, achievementRate } = group;
    return {
      id: groupId,
      name: groupName,
      achievementRate,
    };
  });
  return { userId, userName: `${lastName} ${firstName}`, items };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchJoinGroup = (timeframeId, userId, groupId) =>
    dispatch(joinGroup(timeframeId, userId, groupId))
      .then(result => dispatch(syncJoinGroup(result)));
  const dispatchLeaveGroup = (userId, groupId) =>
    dispatch(leaveGroup(userId, groupId))
      .then(result => dispatch(syncLeaveGroup(result)));
  return { dispatchJoinGroup, dispatchLeaveGroup };
};

const mergeProps = (state, { dispatchJoinGroup, dispatchLeaveGroup }, props) => ({
  ...state,
  ...props,
  dispatchJoinGroup: (userId, groupId) =>
    dispatchJoinGroup(props.timeframeId, userId, groupId),
  dispatchLeaveGroup,
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(UserGroupListContainer);
