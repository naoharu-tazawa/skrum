import React, { Component } from 'react';
import { connect } from 'react-redux';
import { rolesPropTypes } from './propTypes';
import Invitation from './Invitation';

class InvitationContainer extends Component {

  static propTypes = {
    items: rolesPropTypes,
  };

  render() {
    return (
      <Invitation
        items={this.props.items}
      />);
  }
}

const mapStateToProps = (state) => {
  const { roles = [] } = state.userSetting || {};
  const items = roles.map((role) => {
    const { roleAssignmentId, roleName } = role;
    return {
      id: roleAssignmentId,
      name: roleName,
    };
  });
  return { items };
};

export default connect(
  mapStateToProps,
)(InvitationContainer);
