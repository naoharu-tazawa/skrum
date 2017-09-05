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
    const { subject, id, timeframeId } = explodePath(pathname);
    switch (subject) {
      case 'user':
        dispatchFetchUserGroups(id, timeframeId);
        break;
      case 'group':
        dispatchFetchGroupMembers(id, timeframeId);
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
    const { subject, pathname } = this.props;
    const { timeframeId } = explodePath(pathname);
    switch (subject) {
      case 'user':
        return <UserGroupListContainer timeframeId={timeframeId} />;
      case 'group':
        return <GroupMemberListContainer timeframeId={timeframeId} />;
      default:
        return null;
    }
  }

  render() {
    if (this.props.isFetching) {
      return <span className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        {this.renderInfoEditContainer()}
        {this.renderGroupManagementListContainer()}
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching } = state.groupManagement || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserGroups = (userId, timeframeId) =>
    dispatch(fetchUserGroups(userId, timeframeId));
  const dispatchFetchGroupMembers = (groupId, timeframeId) =>
    dispatch(fetchGroupMembers(groupId, timeframeId));
  return {
    dispatchFetchUserGroups,
    dispatchFetchGroupMembers,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupManagementContainer);
