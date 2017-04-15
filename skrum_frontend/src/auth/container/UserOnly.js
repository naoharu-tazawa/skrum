import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import SideBar from '../../common/sidebar/SideBar';

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
    // UserOnly.transfer(this.props);
  }

  componentWillUpdate() {
    // UserOnly.transfer(nextProps);
  }

  render() {
    return (<div>
      <SideBar />
      {this.props.children}
    </div>);
  }
}

const mapStateToProps = state => state;
export default connect(mapStateToProps)(UserOnly);
