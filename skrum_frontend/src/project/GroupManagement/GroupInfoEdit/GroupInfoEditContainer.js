import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import { putGroup, postGroupImage, changeGroupLeader, addGroupPath, deleteGroupPath } from '../action';
import GroupInfoEdit from './GroupInfoEdit';

class GroupInfoEditContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
    dispatchPutGroup: PropTypes.func.isRequired,
    dispatchPostGroupImage: PropTypes.func.isRequired,
    dispatchChangeGroupLeader: PropTypes.func.isRequired,
    dispatchAddGroupPath: PropTypes.func.isRequired,
    dispatchDeleteGroupPath: PropTypes.func.isRequired,
  };

  render() {
    const { group, dispatchPutGroup, dispatchPostGroupImage,
      dispatchChangeGroupLeader, dispatchAddGroupPath, dispatchDeleteGroupPath } = this.props;
    return !group ? null : (
      <GroupInfoEdit
        group={group}
        dispatchPutGroup={dispatchPutGroup}
        dispatchPostGroupImage={dispatchPostGroupImage}
        dispatchChangeGroupLeader={dispatchChangeGroupLeader}
        dispatchAddGroupPath={dispatchAddGroupPath}
        dispatchDeleteGroupPath={dispatchDeleteGroupPath}
      />);
  }
}

const mapStateToProps = (state) => {
  const { groupManagement = {} } = state;
  const { isFetching, group = {} } = groupManagement;
  return isFetching ? {} : { group: group.group };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPutGroup = (id, data) =>
    dispatch(putGroup(id, data));
  const dispatchPostGroupImage = (id, image, mimeType) =>
    dispatch(postGroupImage(id, image, mimeType));
  const dispatchChangeGroupLeader = (groupId, userId, userName) =>
    dispatch(changeGroupLeader(groupId, userId, userName));
  const dispatchAddGroupPath = (groupId, groupPathId) =>
    dispatch(addGroupPath(groupId, groupPathId));
  const dispatchDeleteGroupPath = (groupId, groupPathId) =>
    dispatch(deleteGroupPath(groupId, groupPathId));
  return {
    dispatchPutGroup,
    dispatchPostGroupImage,
    dispatchChangeGroupLeader,
    dispatchAddGroupPath,
    dispatchDeleteGroupPath,
  };
};

const mergeProps = (state, {
  dispatchPutGroup,
  dispatchPostGroupImage,
  dispatchChangeGroupLeader,
  dispatchAddGroupPath,
  dispatchDeleteGroupPath,
}, props) => ({
  ...state,
  ...props,
  dispatchPutGroup,
  dispatchPostGroupImage,
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
