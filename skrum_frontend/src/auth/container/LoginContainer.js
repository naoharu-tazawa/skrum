import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import LoginForm from '../component/LoginForm';
import { startLogin } from '../action';
import { errorType } from '../../util/PropUtil';
import { imgSrc } from '../../util/ResourceUtil';

const defaultStyle = {
  height: '100vh',
  backgroundImage: `url("${imgSrc('top_back.png')}")`,
  backgroundSize: 'cover',
};

const shadowStyle = Object.assign({ height: '100%' }, {});

const containerStyle = {
  width: '300px',
  height: '200px',
  position: 'absolute',
  top: 0,
  left: 0,
  right: 0,
  bottom: 0,
  margin: 'auto',
};

class LoginContainer extends Component {
  static propTypes = {
    isFetching: PropTypes.bool,
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
    return (
      <div style={defaultStyle}>
        <div style={shadowStyle}>
          <div style={containerStyle}>
            <LoginForm
              isFetching={this.props.isFetching}
              handleLoginSubmit={this.props.dispatchLogin}
              error={this.props.error}
            />
          </div>
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching, isAuthorized, error } = state.auth;
  return { isFetching, isAuthorized, error };
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
