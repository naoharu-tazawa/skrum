import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupsPropTypes } from './propTypes';
import { fetchGroups, deleteGroup } from '../action';
import GroupList from './GroupList';

class GroupListContainer extends Component {

  static propTypes = {
    isFetchingGroups: PropTypes.bool.isRequired,
    count: PropTypes.number.isRequired,
    groups: groupsPropTypes.isRequired,
    currentRoleLevel: PropTypes.number.isRequired,
    dispatchFetchGroups: PropTypes.func.isRequired,
    dispatchDeleteGroup: PropTypes.func.isRequired,
  };

  render() {
    const { isFetchingGroups, count, groups, currentRoleLevel,
      dispatchFetchGroups, dispatchDeleteGroup } = this.props;
    return (
      <GroupList
        {...{ isFetchingGroups, count, groups, currentRoleLevel }}
        {...{ dispatchFetchGroups, dispatchDeleteGroup }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { roleLevel: currentRoleLevel } = state.auth || {};
  const { isFetchingGroups, count, groups: items = [] } = state.groupSetting || {};
  const groups = items.map(({ groupId, groupName, groupType }) => ({
    id: groupId,
    name: groupName,
    type: groupType,
  }));
  return { isFetchingGroups, count, groups, currentRoleLevel };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchGroups = (keyword, pageNo) =>
    dispatch(fetchGroups(keyword, pageNo));
  const dispatchDeleteGroup = id =>
    dispatch(deleteGroup(id));
  return {
    dispatchFetchGroups,
    dispatchDeleteGroup,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupListContainer);
