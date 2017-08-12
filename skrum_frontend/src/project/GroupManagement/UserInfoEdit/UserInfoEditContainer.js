import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { userPropTypes } from './propTypes';
import { putUser, postUserImage } from '../action';
import UserInfoEdit from './UserInfoEdit';

class UserInfoEditContainer extends Component {

  static propTypes = {
    user: userPropTypes,
    dispatchPutUser: PropTypes.func.isRequired,
    dispatchPostUserImage: PropTypes.func.isRequired,
  };

  render() {
    const { user, dispatchPutUser, dispatchPostUserImage } = this.props;
    return !user ? null : (
      <UserInfoEdit
        user={user}
        dispatchPutUser={dispatchPutUser}
        dispatchPostUserImage={dispatchPostUserImage}
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
  const dispatchPostUserImage = (id, image, mimeType) =>
    dispatch(postUserImage(id, image, mimeType));
  return { dispatchPutUser, dispatchPostUserImage };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserInfoEditContainer);
