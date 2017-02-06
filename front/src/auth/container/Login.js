import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import LoginForm from '../component/LoginForm';
import { postLogin } from '../action';
import { errorType } from '../../util/PropUtil';
import { shadow } from '../../common/style/guide';
import { imgSrc } from '../../util/ResourceUtil';

const defaultStyle = {
  height: '100vh',
  backgroundImage: `url("${imgSrc('top_back.png')}")`,
  backgroundSize: 'cover',
};

const shadowStyle = Object.assign({ height: '100%' }, shadow);

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

class Login extends Component {
  static propTypes = {
    isFetching: PropTypes.bool,
    handleLoginSubmit: PropTypes.func.isRequired,
    error: errorType,
  };

  render() {
    return (
      <div style={defaultStyle}>
        <div style={shadowStyle}>
          <div style={containerStyle}>
            <LoginForm
              isFetching={this.props.isFetching}
              handleLoginSubmit={this.props.handleLoginSubmit}
              error={this.props.error}
            />
          </div>
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching, error } = state.auth;
  return { isFetching, error };
};

const mapDispatchToProps = (dispatch) => {
  const handleLoginSubmit = ({ username, password }) => dispatch(postLogin({ username, password }));
  return {
    handleLoginSubmit,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Login);
