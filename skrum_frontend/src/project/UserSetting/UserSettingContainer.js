import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import InvitationContainer from './Invitation/InvitationContainer';
import UserListContainer from './UserList/UserListContainer';
import { fetchCompanyRoles } from './action';
import styles from './UserSettingContainer.css';

class UserSettingContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    companyId: PropTypes.number,
    dispatchFetchCompanyRoles: PropTypes.func,
  };

  componentWillMount() {
    const { dispatchFetchCompanyRoles, companyId } = this.props;
    dispatchFetchCompanyRoles(companyId);
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <InvitationContainer />
        <UserListContainer />
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth || {};
  const { isFetching = false } = state.userSetting || {};
  return { isFetching, companyId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchCompanyRoles = companyId =>
    dispatch(fetchCompanyRoles(companyId));
  return {
    dispatchFetchCompanyRoles,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserSettingContainer);
