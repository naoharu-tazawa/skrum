import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import LoginForm from '../component/LoginForm';
import { startLogin } from '../action';
import { errorType } from '../../util/PropUtil';
import styles from './LoginContainer.css';

class LoginContainer extends Component {
  static propTypes = {
    isAuthorized: PropTypes.bool,
    dispatchLogin: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
    this.transfer();
  }

  componentWillReceiveProps(next) {
    this.transfer(next);
  }

  transfer(props = this.props) {
    const { isAuthorized } = props;
    if (isAuthorized) {
      browserHistory.push('/');
    }
  }

  render() {
    const { dispatchLogin, error } = this.props;
    return (
      <div className={styles.default}>
        <div className={styles.shadow}>
          <div className={styles.container}>
            <LoginForm
              handleLoginSubmit={dispatchLogin}
              error={error}
            />
          </div>
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isAuthorized, error } = state.auth;
  return { isAuthorized, error };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchLogin = (emailAddress, password) =>
    dispatch(startLogin(emailAddress, password));
  return { dispatchLogin };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(LoginContainer);
