import React, { Component } from 'react';
import { Field, reduxForm, propTypes } from 'redux-form';
import PropTypes from 'prop-types';
import { errorType } from '../../util/PropUtil';
import styles from './Form.css';

function SubmitButton() {
  return <button type="submit" className={styles.btn}>変更する</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

const validate = (values) => {
  const errors = {};
  if (!values.currentPassword) {
    errors.currentPassword = '入力してください';
  } else if (!/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,20}$/i.test(values.currentPassword)) {
    errors.currentPassword = '半角英数字8字以上20字以下で、アルファベット・数字をそれぞれ1字以上含めてください';
  }

  if (!values.newPassword) {
    errors.newPassword = '入力してください';
  } else if (!/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,20}$/i.test(values.newPassword)) {
    errors.newPassword = '半角英数字8字以上20字以下で、アルファベット・数字をそれぞれ1字以上含めてください';
  }

  if (!values.confirm) {
    errors.confirm = '入力してください';
  } else if (values.newPassword !== values.confirm) {
    errors.confirm = '確認パスワードが異なります';
  }
  return errors;
};

// eslint-disable-next-line react/prop-types
const renderField = ({ input, type, label, meta: { touched, error } }) => (
  <div>
    <label className={styles.label}>{label}</label>
    <span>
      <input {...input} type={type} />
      {touched && error && <div className={styles.warning}>{error}</div>}
    </span>
  </div>
);

class _Form extends Component {

  static propTypes = {
    ...propTypes,
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

  render() {
    const { handleSubmit } = this.props;
    return (
      <form onSubmit={handleSubmit}>
        <div className={styles.floatL}>
          <div className={styles.title} >パスワード変更</div>
          <div className={styles.field}>
            <Field name="currentPassword" type="password" component={renderField} label="現在のパスワード：" />
          </div>
          <div className={styles.field}>
            <Field name="newPassword" type="password" component={renderField} label="新しいパスワード：" />
          </div>
          <div className={styles.field}>
            <Field name="confirm" type="password" component={renderField} label="新しいパスワード（確認）：" />
          </div>
          <div className={styles.field}>
            {this.renderError()}
          </div>
        </div>
        <div className={`${styles.btn_area} ${styles.floatL}`}>{this.renderButton()}</div>
      </form>);
  }
}

const Form = reduxForm({
  form: 'form',
  validate,
})(_Form);

export default Form;
