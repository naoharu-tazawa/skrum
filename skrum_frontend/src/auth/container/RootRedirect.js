import { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';

class RootRedirect extends Component {
  static propTypes = {
    isAuthorized: PropTypes.bool,
    top: PropTypes.string.isRequired,
    login: PropTypes.string.isRequired,
  };

  componentWillMount() {
    const { isAuthorized, top, login } = this.props;
    const to = isAuthorized ? top : login;
    browserHistory.push(to);
  }

  componentWillUpdate() {
    // console.log('================================');
    const { isAuthorized, top, login } = this.props;
    const to = isAuthorized ? top : login;
    browserHistory.push(to);
  }

  render() {
    return null;
  }
}

const mapStateToProps = (state) => {
  const { isAuthorized } = state.auth;
  return { isAuthorized };
};
export default connect(mapStateToProps)(RootRedirect);
