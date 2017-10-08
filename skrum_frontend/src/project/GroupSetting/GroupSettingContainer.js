import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import CreateGroupContainer from './CreateGroup/CreateGroupContainer';
import GroupListContainer from './GroupList/GroupListContainer';
import { fetchGroups } from './action';
import styles from './GroupSettingContainer.css';

class GroupSettingContainer extends Component {

  static propTypes = {
    keyword: PropTypes.string,
    pageNo: PropTypes.number,
    dispatchFetchGroups: PropTypes.func.isRequired,
  };

  render() {
    const { keyword, pageNo, dispatchFetchGroups } = this.props;
    return (
      <div className={styles.container}>
        <CreateGroupContainer {...{ keyword, pageNo, dispatchFetchGroups }} />
        <GroupListContainer {...{ dispatchFetchGroups }} />
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { keyword, pageNo } = state.groupSetting || {};
  return { keyword, pageNo };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchGroups = (keyword, pageNo) =>
    dispatch(fetchGroups(keyword, pageNo));
  return {
    dispatchFetchGroups,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(GroupSettingContainer);
