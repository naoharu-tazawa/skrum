import React, { Component } from 'react';
import { Field, reduxForm } from 'redux-form';
import PropTypes from 'prop-types';
import { errorType } from '../../../util/PropUtil';
import { rolesPropTypes } from './propTypes';
import styles from './InvitationForm.css';

function SubmitButton() {
  return <button type="submit" className={styles.btn}>招待する</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

const validate = (values) => {
  const errors = {};
  if (!values.emailAddress) {
    errors.emailAddress = '入力してください';
  }

  return errors;
};

// eslint-disable-next-line react/prop-types
const renderField = ({ input, type, label, meta: { touched, error } }) => (
  <span>
    <label className={styles.label}>{label}</label>
    <span>
      <input className={styles.input} {...input} type={type} placeholder="メールアドレスを入力して招待メールを送る" />
      {touched && error && <div className={styles.warning}>{error}</div>}
    </span>
  </span>
);

class _InvitationForm extends Component {

  static propTypes = {
    items: rolesPropTypes.isRequired,
    isPosting: PropTypes.bool,
    error: errorType,
    handleSubmit: PropTypes.func,
  };

  renderButton() {
    return this.props.isPosting ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    const { handleSubmit, items } = this.props;
    return (
      <form onSubmit={handleSubmit}>
        <span className={styles.floatL}><Field name="emailAddress" type="email" component={renderField} label="" /></span>
        <span className={styles.floatL}>
          <Field className={styles.pulldown} name="roleAssignmentId" component="select">
            <option value="">権限を選択してください</option>
            {items.map(item =>
              <option key={item.id} value={item.id}>{item.name}</option>)}
          </Field>
        </span>
        <span className={`${styles.btn_area} ${styles.floatL}`}>{this.renderButton()}</span>
      </form>
    );
  }
}

const InvitationForm = reduxForm({
  form: 'form',
  validate,
})(_InvitationForm);

export default InvitationForm;
