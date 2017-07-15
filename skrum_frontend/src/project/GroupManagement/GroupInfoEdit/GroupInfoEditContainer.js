import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import { putGroup } from '../action';
import GroupInfoEdit from './GroupInfoEdit';

class GroupInfoEditContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
    dispatchPutGroup: PropTypes.func.isRequired,
  };

  render() {
    const { group, dispatchPutGroup } = this.props;
    return !group ? null : (
      <GroupInfoEdit
        group={group}
        infoLink="./"
        dispatchPutGroup={dispatchPutGroup}
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
  return { dispatchPutGroup };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupInfoEditContainer);
