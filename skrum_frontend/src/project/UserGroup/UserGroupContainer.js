import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import UserInfoEditContainer from '../UserInfoEdit/UserInfoEditContainer';
import UserGroupListContainer from '../UserGroupList/UserGroupListContainer';
import { fetchUserGroups } from './UserGroupActions';
import styles from './UserGroupContainer.css';

class UserGroupContainer extends Component {

  static propTypes = {
    children: PropTypes.oneOfType([
      PropTypes.element,
      PropTypes.arrayOf([PropTypes.element]),
    ]),
    dispatchFetchUserGroups: PropTypes.func,
  };

  static defaultProps = {
  };

  componentWillMount() {
    this.props.dispatchFetchUserGroups();
  }

  render() {
    return (
      <div className={styles.container}>
        <div className={styles.userInfoEdit}>
          <UserInfoEditContainer />
        </div>
        <div className={styles.userGroupList}>
          <UserGroupListContainer />
          {this.props.children}
        </div>
      </div>);
  }
}

const mapStateToProps = state => state;

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserGroups = userId => dispatch(fetchUserGroups(userId));
  return { dispatchFetchUserGroups };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserGroupContainer);
