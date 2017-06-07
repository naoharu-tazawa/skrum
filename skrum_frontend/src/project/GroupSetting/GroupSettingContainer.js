import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import CreateGroupContainer from './CreateGroup/CreateGroupContainer';
import GroupListContainer from './GroupList/GroupListContainer';
import { fetchCompanyRoles } from './action';
import styles from './GroupSettingContainer.css';

class GroupSettingContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    companyId: PropTypes.number,
    dispatchFetchCompanyRoles: PropTypes.func,
  };

  componentWillMount() {
    const { dispatchFetchCompanyRoles, companyId } = this.props;
    dispatchFetchCompanyRoles(companyId);
  }

  componentWillReceiveProps() {
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <CreateGroupContainer />
        <GroupListContainer />
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
)(GroupSettingContainer);
