import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { errorType } from '../../util/PropUtil';
import styles from './LoginForm.css';

function SubmitButton() {
  return (
    <button className={styles.defaultButton}>
      ログイン
    </button>
  );
}

function DisabledButton() {
  return <div className={styles.disableButton} />;
}

class LoginForm extends Component {
  static propTypes = {
    isFetching: PropTypes.bool,
    handleLoginSubmit: PropTypes.func.isRequired,
    error: errorType,
  };

  handleSubmit(e) {
    const target = e.target;
    e.preventDefault();
    this.props.handleLoginSubmit(
      target.email.value.trim(),
      target.password.value.trim(),
    );
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
    return this.props.isFetching ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    return (
      <div>
        <p className={styles.default}>Skrum Login</p>
        <br />
        <form onSubmit={e => this.handleSubmit(e)}>
          <input type="text" id="email" placeholder="Email" className={styles.defaultInput} />
          <br />
          <input type="password" id="password" placeholder="Password" className={styles.defaultInput} />
          {this.renderError()}
          <br />
          <div>
            {this.renderButton()}
          </div>
        </form>
      </div>);
  }
}

export default LoginForm;
