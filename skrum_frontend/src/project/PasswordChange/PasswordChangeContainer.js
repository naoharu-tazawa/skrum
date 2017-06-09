import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { errorType } from '../../util/PropUtil';
import { putUserChangepassword } from './action';
import styles from './PasswordChangeContainer.css';
import Form from './Form';

class PasswordChangeContainer extends Component {

  static propTypes = {
    userId: PropTypes.number,
    isProcessing: PropTypes.bool,
    dispatchPutUserChangepassword: PropTypes.func,
    error: errorType,
  };

  constructor(props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleSubmit(values) {
    this.props.dispatchPutUserChangepassword(
      this.props.userId,
      values.currentPassword,
      values.newPassword,
    );
  }

  render() {
    const { isProcessing, error } = this.props;
    return (
      <div className={styles.container}>
        <Form
          isProcessing={isProcessing}
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
