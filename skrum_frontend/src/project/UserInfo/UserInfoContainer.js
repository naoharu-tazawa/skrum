import React, { Component } from 'react';
import { connect } from 'react-redux';
import { userPropTypes } from './propTypes';
import UserInfo from './UserInfo';

class UserInfoContainer extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
  };

  render() {
    const { user } = this.props;
    return (
      <UserInfo
        user={user}
        infoLink="./"
      />);
  }
}

const mapStateToProps = (state) => {
  const { user = {} } = state.user.data || {};
  return { user };
};

export default connect(
  mapStateToProps,
)(UserInfoContainer);
