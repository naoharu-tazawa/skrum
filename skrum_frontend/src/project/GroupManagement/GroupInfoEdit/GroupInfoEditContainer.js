import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import { putGroup, changeGroupLeader } from '../action';
import GroupInfoEdit from './GroupInfoEdit';
import { RoleLevel } from '../../../util/UserUtil';

class GroupInfoEditContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
    roleLevel: PropTypes.number.isRequired,
    dispatchPutGroup: PropTypes.func.isRequired,
    dispatchChangeGroupLeader: PropTypes.func,
  };

  render() {
    const { group, roleLevel, dispatchPutGroup, dispatchChangeGroupLeader } = this.props;
    return !group ? null : (
      <GroupInfoEdit
        group={group}
        roleLevel={roleLevel}
        infoLink="./"
        dispatchPutGroup={dispatchPutGroup}
        dispatchChangeGroupLeader={dispatchChangeGroupLeader}
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
  return { dispatchPutGroup, dispatchChangeGroupLeader };
};

const mergeProps = (state, { dispatchPutGroup, dispatchChangeGroupLeader }, props) => ({
  ...state,
  ...props,
  dispatchPutGroup,
  dispatchChangeGroupLeader: (userId, userName) =>
    dispatchChangeGroupLeader(state.group.groupId, userId, userName),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(GroupInfoEditContainer);
