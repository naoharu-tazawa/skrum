import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';

class UserOnly extends Component {
  static propTypes = {
    children: PropTypes.element.isRequired,
  };

  static transfer(props) {
    if (!props.auth.isLoggedIn) {
      browserHistory.push('/login');
    }
  }

  componentWillMount() {
    UserOnly.transfer(this.props);
  }

  componentWillUpdate(nextProps) {
    UserOnly.transfer(nextProps);
  }

  render() {
    return <div>{this.props.children}</div>;
  }
}

const mapStateToProps = state => state;
export default connect(mapStateToProps)(UserOnly);
