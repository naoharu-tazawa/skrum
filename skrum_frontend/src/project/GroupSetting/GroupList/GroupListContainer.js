import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupsPropTypes } from './propTypes';
import { deleteGroup } from '../action';
import GroupList from './GroupList';

class GroupListContainer extends Component {

  static propTypes = {
    keyword: PropTypes.string,
    pageNo: PropTypes.number,
    isFetchingGroups: PropTypes.bool.isRequired,
    count: PropTypes.number.isRequired,
    groups: groupsPropTypes.isRequired,
    currentRoleLevel: PropTypes.number.isRequired,
    dispatchFetchGroups: PropTypes.func.isRequired,
    dispatchDeleteGroup: PropTypes.func.isRequired,
  };

  render() {
    const { keyword, pageNo, isFetchingGroups, count, groups, currentRoleLevel,
      dispatchFetchGroups, dispatchDeleteGroup } = this.props;
    return (
      <GroupList
        {...{ keyword, pageNo, isFetchingGroups, count, groups, currentRoleLevel }}
        {...{ dispatchFetchGroups, dispatchDeleteGroup }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { roleLevel: currentRoleLevel } = state.auth || {};
  const { keyword, pageNo, isFetchingGroups, count, groups: items = [] } = state.groupSetting || {};
  const groups = items.map(({ groupId, groupName, groupType }) => ({
    id: groupId,
    name: groupName,
    type: groupType,
  }));
  return { keyword, pageNo, isFetchingGroups, count, groups, currentRoleLevel };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchDeleteGroup = id =>
    dispatch(deleteGroup(id));
  return {
    dispatchDeleteGroup,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupListContainer);
