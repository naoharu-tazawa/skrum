import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberList from './GroupMemberList';
import { RoleLevel } from '../../../util/UserUtil';

class GroupMemberListContainer extends Component {

  static propTypes = {
    items: groupMembersPropTypes,
    roleLevel: PropTypes.number.isRequired,
  };

  render() {
    const { items, roleLevel } = this.props;
    return (
      <GroupMemberList
        items={items}
        roleLevel={roleLevel}
      />);
  }
}

const mapStateToProps = (state) => {
  const { roleLevel = RoleLevel.BASIC } = state.auth || {};
  const { members = [] } = state.groupManagement.group || {};
  const items = members.map((member) => {
    const { userId, name, position, achievementRate, lastLogin } = member;
    return {
      id: userId,
      name,
      position,
      achievementRate,
      lastLogin,
    };
  });
  return { roleLevel, items };
};

export default connect(
  mapStateToProps,
)(GroupMemberListContainer);
