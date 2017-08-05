import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import { putGroup, changeGroupLeader, addGroupPath, deleteGroupPath } from '../action';
import GroupInfoEdit from './GroupInfoEdit';
import { RoleLevel } from '../../../util/UserUtil';

class GroupInfoEditContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
    roleLevel: PropTypes.number.isRequired,
    dispatchPutGroup: PropTypes.func.isRequired,
    dispatchChangeGroupLeader: PropTypes.func.isRequired,
    dispatchAddGroupPath: PropTypes.func.isRequired,
    dispatchDeleteGroupPath: PropTypes.func.isRequired,
  };

  render() {
    const { group, roleLevel, dispatchPutGroup, dispatchChangeGroupLeader,
      dispatchAddGroupPath, dispatchDeleteGroupPath } = this.props;
    return !group ? null : (
      <GroupInfoEdit
        group={group}
        roleLevel={roleLevel}
        infoLink="./"
        dispatchPutGroup={dispatchPutGroup}
        dispatchChangeGroupLeader={dispatchChangeGroupLeader}
        dispatchAddGroupPath={dispatchAddGroupPath}
        dispatchDeleteGroupPath={dispatchDeleteGroupPath}
      />);
  }
}

const mapStateToProps = (state) => {
  const { auth = {}, groupManagement = {} } = state;
  const { roleLevel = RoleLevel.BASIC } = auth;
  const { isFetching, group = {} } = groupManagement;
  return { roleLevel, ...(isFetching ? {} : { group: group.group }) };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPutGroup = (id, data) =>
    dispatch(putGroup(id, data));
  const dispatchChangeGroupLeader = (groupId, userId, userName) =>
    dispatch(changeGroupLeader(groupId, userId, userName));
  const dispatchAddGroupPath = (groupId, groupPathId) =>
    dispatch(addGroupPath(groupId, groupPathId));
  const dispatchDeleteGroupPath = (groupId, groupPathId) =>
    dispatch(deleteGroupPath(groupId, groupPathId));
  return {
    dispatchPutGroup,
    dispatchChangeGroupLeader,
    dispatchAddGroupPath,
    dispatchDeleteGroupPath,
  };
};

const mergeProps = (state, {
  dispatchPutGroup,
  dispatchChangeGroupLeader,
  dispatchAddGroupPath,
  dispatchDeleteGroupPath,
}, props) => ({
  ...state,
  ...props,
  dispatchPutGroup,
  dispatchChangeGroupLeader: (userId, userName) =>
    dispatchChangeGroupLeader(state.group.groupId, userId, userName),
  dispatchAddGroupPath,
  dispatchDeleteGroupPath,
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(GroupInfoEditContainer);
