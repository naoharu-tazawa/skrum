import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { toastr } from 'react-redux-toastr';
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

  componentWillReceiveProps(next) {
    this.result(next);
  }

  result(props = this.props) {
    const { isProcessing, error } = props;
    if (!isProcessing) {
      if (!isProcessing && !error) {
        toastr.info('パスワードを変更しました');
      } else {
        toastr.error('パスワードが正しくありません');
      }
    }
  }

  handleSubmit(data) {
    this.props.dispatchPutUserChangepassword(
      this.props.userId,
      data.currentPassword,
      data.newPassword,
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
  const { isProcessing, error } = state.setting || {};
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
