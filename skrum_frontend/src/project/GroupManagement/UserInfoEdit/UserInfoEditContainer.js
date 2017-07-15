import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { userPropTypes } from './propTypes';
import { putUser } from '../action';
import UserInfoEdit from './UserInfoEdit';

class UserInfoEditContainer extends Component {

  static propTypes = {
    user: userPropTypes,
    dispatchPutUser: PropTypes.func.isRequired,
  };

  render() {
    const { user, dispatchPutUser } = this.props;
    return !user ? null : (
      <UserInfoEdit
        user={user}
        infoLink="./"
        dispatchPutUser={dispatchPutUser}
      />);
  }
}

const mapStateToProps = (state) => {
  const { groupManagement = {} } = state;
  const { isFetching, user = {} } = groupManagement;
  return isFetching ? {} : { user: user.user };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPutUser = (id, data) =>
    dispatch(putUser(id, data));
  return { dispatchPutUser };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserInfoEditContainer);
