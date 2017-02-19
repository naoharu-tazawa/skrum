import React, { Component, PropTypes } from 'react';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';

class Anonymous extends Component {
  static propTypes = {
    children: PropTypes.element.isRequired,
    auth: PropTypes.object, // eslint-disable-line react/forbid-prop-types
  };

  static transfer(auth) {
    if (auth && auth.isLoggedIn) {
      browserHistory.push('/piyo');
    }
  }

  componentWillMount() {
    Anonymous.transfer(this.props.auth);
  }

  componentWillUpdate(nextProps) {
    Anonymous.transfer(nextProps.auth);
  }

  render() {
    return (
      <div>
        {this.props.children}
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { auth } = state;
  return { auth };
};

export default connect(mapStateToProps)(Anonymous);
