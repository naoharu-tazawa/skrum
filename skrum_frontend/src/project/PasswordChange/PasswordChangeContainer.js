import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { errorType } from '../../util/PropUtil';
import styles from './PasswordChangeContainer.css';
import { putUserChangepassword } from './action';

function SubmitButton() {
  return <button className={styles.btn}>変更する</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

class PasswordChangeContainer extends Component {

  static propTypes = {
    userId: PropTypes.number,
    isProcessing: PropTypes.bool,
    dispatchPutUserChangepassword: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
  }

  componentWillReceiveProps() {
  }

  handleSubmit(e) {
    const target = e.target;
    e.preventDefault();
    this.props.dispatchPutUserChangepassword(
      this.props.userId,
      target.currentPassword.value.trim(),
      target.newPassword.value.trim(),
    );

    this.currentPassword.value = '';
    this.newPassword.value = '';
    this.confirm.value = '';
  }

  renderError() {
    if (this.props.error) {
      return (<pre>
        <p>エラーが発生しました</p>
        <br />
        {this.props.error.message}
      </pre>);
    }
  }

  renderButton() {
    return this.props.isProcessing ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    return (
      <div className={styles.container}>
        <form onSubmit={e => this.handleSubmit(e)}>
          <table className={styles.floatL}>
            <thead>
              <tr>
                <th className={styles.title} colSpan="2">パスワード変更</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><div className={styles.td}>現在のパスワード：</div></td>
                <td><input id="currentPassword" type="password" ref={input => (this.currentPassword = input)} /></td>
              </tr>
              <tr>
                <td><div className={styles.td}>新しいパスワード：</div></td>
                <td><input id="newPassword" type="password" ref={input => (this.newPassword = input)} /></td>
              </tr>
              <tr>
                <td><div className={styles.td}>新しいパスワードの確認：</div></td>
                <td><input type="password" ref={input => (this.confirm = input)} /></td>
              </tr>
              <tr>
                <td colSpan="2"><div className={styles.td}>{this.renderError()}</div></td>
              </tr>
            </tbody>
          </table>
          <div className={`${styles.btn_area} ${styles.floatL}`}>{this.renderButton()}</div>
        </form>
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
