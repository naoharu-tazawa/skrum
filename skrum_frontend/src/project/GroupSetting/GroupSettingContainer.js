import React, { Component } from 'react';
import CreateGroupContainer from './CreateGroup/CreateGroupContainer';
import GroupListContainer from './GroupList/GroupListContainer';
import styles from './GroupSettingContainer.css';

export default class GroupSettingContainer extends Component {

  render() {
    return (
      <div className={styles.container}>
        <CreateGroupContainer />
        <GroupListContainer />
      </div>);
  }
}
