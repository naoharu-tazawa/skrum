import React, { Component } from 'react';
import { Field, reduxForm } from 'redux-form';
import PropTypes from 'prop-types';
import { errorType } from '../../util/PropUtil';
import styles from './Form.css';

function SubmitButton() {
  return <button type="submit" className={styles.btn}>変更する</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

class _Form extends Component {

  static propTypes = {
    isProcessing: PropTypes.bool,
    error: errorType,
    handleSubmit: PropTypes.func,
  };

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

  const validate = values => {
    const errors = {};
    if (!values.currentPassword) {
      errors.currentPassword = "現在のパスワードを入力してください。";
    }
    if (!values.newPassword) {
      errors.newPassword = "新しいパスワードを入力してください。";
    }
    if (!values.confirm) {
      errors.confirm = "新しいパスワードの確認を入力してください。";
    }
    return errors;
  };

  render() {
    const { handleSubmit } = this.props;
    return (
      <form onSubmit={handleSubmit}>
        <table className={styles.floatL}>
          <thead>
            <tr>
              <th className={styles.title} colSpan="2">パスワード変更</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><div className={styles.td}><label htmlFor="currentPassword">現在のパスワード：</label></div></td>
              <td><Field name="currentPassword" component="input" type="password" /></td>
            </tr>
            <tr>
              <td><div className={styles.td}><label htmlFor="newPassword">新しいパスワード：</label></div></td>
              <td><Field name="newPassword" component="input" type="password" /></td>
            </tr>
            <tr>
              <td><div className={styles.td}><label htmlFor="confirm">新しいパスワードの確認：</label></div></td>
              <td><Field name="confirm" component="input" type="password" /></td>
            </tr>
            <tr>
              <td colSpan="2"><div className={styles.td}>{this.renderError()}</div></td>
            </tr>
          </tbody>
        </table>
        <div className={`${styles.btn_area} ${styles.floatL}`}>{this.renderButton()}</div>
      </form>);
  }
}

const Form = reduxForm({
  form: 'form',
  validate,
  fields: ['currentPassword', 'newPassword', 'confirm']
})(_Form);

export default Form;
