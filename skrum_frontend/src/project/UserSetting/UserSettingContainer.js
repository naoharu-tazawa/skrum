import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import InvitationContainer from './Invitation/InvitationContainer';
import UserListContainer from './UserList/UserListContainer';
import styles from './UserSettingContainer.css';

class UserSettingContainer extends Component {

  static propTypes = {
    isFetchingRoles: PropTypes.bool,
  };

  render() {
    if (this.props.isFetchingRoles) {
      return <span className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <InvitationContainer />
        <UserListContainer />
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetchingRoles } = state.userSetting || {};
  return { isFetchingRoles };
};

export default connect(
  mapStateToProps,
)(UserSettingContainer);
