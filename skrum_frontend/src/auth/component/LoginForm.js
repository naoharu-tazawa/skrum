import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { errorType } from '../../util/PropUtil';
import { imgSrc } from '../../util/ResourceUtil';

const defaultPStyle = {
  color: '#fff',
  textAlign: 'center',
};

const defaultInputStyle = {
  width: '100%',
  border: 0,
  padding: '10px',
  margin: '0 -10px 20px',
  borderRadius: '3px',
};

const defaultButtonStyle = {
  display: 'block',
  margin: 'auto',
  border: 'none',
  width: '50%',
  padding: '10px 0',
  borderRadius: '3px',
  cursor: 'pointer',
};

const disableButtonStyle = {
  height: '50px',
  background: `url("${imgSrc('./rolling.svg')}") center no-repeat`,
};

function SubmitButton() {
  return (
    <button style={defaultButtonStyle}>
      ログイン
    </button>
  );
}

function DisabledButton() {
  return <div style={disableButtonStyle} />;
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
        <p style={defaultPStyle}>Skrum Login</p>
        <br />
        <form onSubmit={e => this.handleSubmit(e)}>
          <input type="text" id="email" placeholder="Email" style={defaultInputStyle} />
          <br />
          <input type="password" id="password" placeholder="Password" style={defaultInputStyle} />
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
