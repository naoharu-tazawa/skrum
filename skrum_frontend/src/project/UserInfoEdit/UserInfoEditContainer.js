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
  const { isFetching } = state.userGroupData;
  const { user = {} } = state.userGroupData.data || {};
  return isFetching ? {} : { user };
};

export default connect(
  mapStateToProps,
)(UserInfoEditContainer);
