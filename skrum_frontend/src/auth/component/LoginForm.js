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
    // isPosting: PropTypes.bool,
    handleLoginSubmit: PropTypes.func.isRequired,
    error: errorType,
  };

  handleSubmit(e) {
    const target = e.target;
    e.preventDefault();
    this.setState({ isPosting: true });
    this.props.handleLoginSubmit(
      target.email.value.trim(),
      target.password.value.trim(),
    ).then(() => this.setState({ isPosting: false }));
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
    const { isPosting } = this.state || {};
    return isPosting ? <DisabledButton /> : <SubmitButton />;
  }

  render() {
    return (
      <div className={styles.container}>
        <p className={styles.default}>Skrum Login</p>
        <br />
        <form onSubmit={this.handleSubmit.bind(this)}>
          <input type="email" id="email" placeholder="Email" className={styles.defaultInput} />
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
