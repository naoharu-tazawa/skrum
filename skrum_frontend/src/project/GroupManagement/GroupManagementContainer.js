import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import UserInfoEditContainer from './UserInfoEdit/UserInfoEditContainer';
import GroupInfoEditContainer from './GroupInfoEdit/GroupInfoEditContainer';
import UserGroupListContainer from './UserGroupList/UserGroupListContainer';
import GroupMemberListContainer from './GroupMemberList/GroupMemberListContainer';
import { fetchUserGroups, fetchGroupMembers } from './action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import styles from './GroupManagementContainer.css';

class GroupManagementContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    subject: PropTypes.string,
    dispatchFetchUserGroups: PropTypes.func,
    dispatchFetchGroupMembers: PropTypes.func,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchGroupManagement(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchGroupManagement(pathname);
    }
  }

  fetchGroupManagement(pathname) {
    const {
      dispatchFetchUserGroups,
      dispatchFetchGroupMembers,
    } = this.props;
    const { section, id, timeframeId } = explodePath(pathname);
    switch (section) {
      case 'user':
        dispatchFetchUserGroups(id, timeframeId);
        break;
      case 'group':
        dispatchFetchGroupMembers(id);
        break;
      default:
        break;
    }
  }

  renderInfoEditContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserInfoEditContainer />;
      case 'group':
        return <GroupInfoEditContainer />;
      default:
        return null;
    }
  }

  renderGroupManagementListContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserGroupListContainer />;
      case 'group':
        return <GroupMemberListContainer />;
      default:
        return null;
    }
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        {this.renderInfoEditContainer()}
        {this.renderGroupManagementListContainer()}
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching = false } = state.groupManagement || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserGroups = (userId, timeframeId) =>
    dispatch(fetchUserGroups(userId, timeframeId));
  const dispatchFetchGroupMembers = groupId =>
    dispatch(fetchGroupMembers(groupId));
  return {
    dispatchFetchUserGroups,
    dispatchFetchGroupMembers,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupManagementContainer);
