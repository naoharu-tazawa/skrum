import React, { Component } from 'react';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import GroupInfoEdit from './GroupInfoEdit';

class GroupInfoEditContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
  };

  render() {
    const { group } = this.props;
    return !group ? null : (
      <GroupInfoEdit
        group={group}
        infoLink="./"
      />);
  }
}

const mapStateToProps = (state) => {
  const { groupManagement = {} } = state;
  const { isFetching, group = {} } = groupManagement;
  return isFetching ? {} : { group: group.group };
};

export default connect(
  mapStateToProps,
)(GroupInfoEditContainer);
