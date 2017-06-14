import React, { Component } from 'react';
import { Field, reduxForm, propTypes } from 'redux-form';
import PropTypes from 'prop-types';
import { errorType } from '../../util/PropUtil';
import styles from './Form.css';

function SubmitButton() {
  return <button type="submit" className={styles.btn}>投稿する</button>;
}

function DisabledButton() {
  return <div className={styles.disable_btn} />;
}

class _Form extends Component {

  static propTypes = {
    ...propTypes,
    isProcessing: PropTypes.bool,
    error: errorType,
    handleSubmit: PropTypes.func,
  };

  renderButton() {
    return this.props.isPosting ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    const { handleSubmit } = this.props;
    return (
      <form onSubmit={handleSubmit}>
        <h1 className={styles.ttl_section}>新規投稿作成</h1>
        <div className={styles.cont_box}>
          <div className={styles.user_name}>
            <dl>
              <dt><img src="/img/common/icn_user.png" alt="" /></dt>
              <dd />
            </dl>
          </div>
          <div className={styles.text_area}>
            <Field
              name="post"
              component="textarea"
              placeholder="仕事の状況はどうですか？"
            />
          </div>
          <div className={styles.btn}>{this.renderButton()}</div>
        </div>
      </form>);
  }
}

const Form = reduxForm({
  form: 'form',
})(_Form);

export default Form;
