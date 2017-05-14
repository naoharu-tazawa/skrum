import React, { Component } from 'react';
import { connect } from 'react-redux';
import { groupPropTypes } from './propTypes';
import GroupInfo from './GroupInfo';

class GroupInfoContainer extends Component {

  static propTypes = {
    group: groupPropTypes,
  };

  render() {
    const { group } = this.props;
    return !group ? null : (
      <GroupInfo
        group={group}
        infoLink="./"
      />);
  }
}

const mapStateToProps = (state) => {
  const { basics = {} } = state;
  const { isFetching, group = {} } = basics;
  return isFetching ? {} : { group: group.group };
};

export default connect(
  mapStateToProps,
)(GroupInfoContainer);
