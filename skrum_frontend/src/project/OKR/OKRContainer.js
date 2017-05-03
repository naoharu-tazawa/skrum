import React, { Component } from 'react';
import UserInfoContainer from '../UserInfo/UserInfoContainer';
import OKRListContainer from '../OKRList/OKRListContainer';
import styles from './OKRContainer.css';

export default class OKRContainer extends Component {

  render() {
    return (
      <div className={styles.container}>
        <div className={styles.userInfo}>
          <UserInfoContainer />
        </div>
        <div className={styles.okrList}>
          <OKRListContainer />
        </div>
      </div>);
  }
}
