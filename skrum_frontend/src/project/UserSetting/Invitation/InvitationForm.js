import React, { Component } from 'react';
import { Field, reduxForm } from 'redux-form';
import PropTypes from 'prop-types';
import UserRolesDropdown from '../UserRolesDropdown';
import { withSelectReduxField } from '../../../util/FormUtil';
import { errorType } from '../../../util/PropUtil';
import styles from './InvitationForm.css';

const validate = ({ emailAddress, roleAssignmentId }) => ({
  emailAddress: !emailAddress && '入力してください',
  roleAssignmentId: !roleAssignmentId && '選択してください',
});

// eslint-disable-next-line react/prop-types
const renderField = ({ input, type, label, placeholder, meta: { touched, error } }) => (
  <span>
    <label className={styles.label}>{label}</label>
    <span>
      <input className={styles.input} {...input} type={type} placeholder={placeholder} />
      {touched && error && <div className={styles.warning}>{error}</div>}
    </span>
  </span>
);

// eslint-disable-next-line react/prop-types
const renderSelect = ({ input: { name }, meta: { touched, error } }) => (
  <span>
    {withSelectReduxField(UserRolesDropdown, name, { className: styles.select })}
    {touched && error && <div className={styles.warning}>{error}</div>}
  </span>
);

class InvitationForm extends Component {

  static propTypes = {
    isPostingInvite: PropTypes.bool,
    error: errorType,
    handleSubmit: PropTypes.func,
  };

  render() {
    const { handleSubmit, isPostingInvite } = this.props;
    return (
      <form onSubmit={handleSubmit}>
        <span className={styles.floatL}>
          <Field
            name="emailAddress"
            type="email"
            component={renderField}
            label=""
            placeholder="メールアドレスを入力して招待メールを送る"
          />
        </span>
        <span className={styles.floatL} style={{ marginLeft: '30px' }}>
          <Field name="roleAssignmentId" component={renderSelect} />
        </span>
        <span className={`${styles.btn_area} ${styles.floatL}`}>
          {!isPostingInvite && <button type="submit" className={styles.btn}>招待する</button>}
          {isPostingInvite && <div className={styles.disable_btn} />}
        </span>
      </form>
    );
  }
}

export const formName = 'Invitation';

export default reduxForm({
  form: formName,
  validate,
})(InvitationForm);
