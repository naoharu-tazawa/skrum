import React, { Component } from 'react';
import { connect } from 'react-redux';
import { userPropTypes } from './propTypes';
import UserInfo from './UserInfo';

class UserInfoContainer extends Component {

  static propTypes = {
    user: userPropTypes,
  };

  render() {
    const { user } = this.props;
    return !user ? null : (
      <UserInfo
        user={user}
        infoLink="./"
      />);
  }
}

const mapStateToProps = (state) => {
  const { basics = {} } = state;
  const { isFetching, user = {} } = basics;
  return isFetching ? {} : { user: user.user };
};

export default connect(
  mapStateToProps,
)(UserInfoContainer);
