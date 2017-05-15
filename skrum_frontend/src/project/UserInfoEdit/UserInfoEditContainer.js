import React, { Component } from 'react';
import { connect } from 'react-redux';
import { userPropTypes } from './propTypes';
import UserInfoEdit from './UserInfoEdit';

class UserInfoEditContainer extends Component {

  static propTypes = {
    user: userPropTypes,
  };

  render() {
    const { user } = this.props;
    return !user ? null : (
      <UserInfoEdit
        user={user}
        infoLink="./"
      />);
  }
}

const mapStateToProps = (state) => {
  const { groupManagement = {} } = state;
  const { isFetching, user = {} } = groupManagement;
  return isFetching ? {} : { user: user.user };
};

export default connect(
  mapStateToProps,
)(UserInfoEditContainer);
