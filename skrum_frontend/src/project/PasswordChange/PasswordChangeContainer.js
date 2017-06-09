import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { errorType } from '../../util/PropUtil';
import { putUserChangepassword } from './action';
import styles from './PasswordChangeContainer.css';
import { Form } from './Form';

class PasswordChangeContainer extends Component {

  static propTypes = {
    userId: PropTypes.number,
    isProcessing: PropTypes.bool,
    dispatchPutUserChangepassword: PropTypes.func,
    error: errorType,
  };

  handleSubmit(e) {
    const target = e.target;
    e.preventDefault();
    this.props.dispatchPutUserChangepassword(
      this.props.userId,
      target.currentPassword.value.trim(),
      target.newPassword.value.trim(),
    );
  }

  render() {
    const { userId, isProcessing, dispatchPutUserChangepassword, error } = this.props;
    return (
      <div className={styles.container}>
        <Form
          userId={userId}
          isProcessing={isProcessing}
          dispatchPutUserChangepassword={dispatchPutUserChangepassword}
          error={error}
          onSubmit={this.handleSubmit}
        />
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { userId } = state.auth;
  const { isProcessing = false, error } = state.setting || {};
  return { userId, isProcessing, error };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPutUserChangepassword = (userId, currentPassword, newPassword) =>
    dispatch(putUserChangepassword(userId, currentPassword, newPassword));
  return { dispatchPutUserChangepassword };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PasswordChangeContainer);
