import React, { Component } from 'react';
import { connect } from 'react-redux';
import { rolesPropTypes } from './propTypes';
import CreateGroup from './CreateGroup';

class CreateGroupContainer extends Component {

  static propTypes = {
    items: rolesPropTypes,
  };

  render() {
    return (
      <CreateGroup
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
)(CreateGroupContainer);
