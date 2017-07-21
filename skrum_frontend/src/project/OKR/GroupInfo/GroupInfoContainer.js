import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import GroupInfo from './GroupInfo';
import { changeGroupLeader } from '../action';

class GroupInfoContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
    dispatchChangeGroupLeader: PropTypes.func,
  };

  render() {
    const { group, dispatchChangeGroupLeader } = this.props;
    return !group ? null : (
      <GroupInfo
        group={group}
        infoLink="./"
        dispatchChangeGroupLeader={dispatchChangeGroupLeader}
      />);
  }
}

const mapStateToProps = (state) => {
  const { basics = {} } = state;
  const { isFetching, group = {} } = basics;
  return isFetching ? {} : { group: group.group };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchChangeGroupLeader = (groupId, userId, userName) =>
    dispatch(changeGroupLeader(groupId, userId, userName));
  return {
    dispatchChangeGroupLeader,
  };
};

const mergeProps = (state, { dispatchChangeGroupLeader }, props) => {
  return {
    ...state,
    ...props,
    dispatchChangeGroupLeader: (userId, userName) =>
      dispatchChangeGroupLeader(state.group.groupId, userId, userName),
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(GroupInfoContainer);
