import React, { Component } from 'react';
import { userGroupsPropTypes } from './propTypes';
import UserGroupBar from './UserGroupBar';
import styles from './UserGroupList.css';

export default class UserGroupList extends Component {

  static propTypes = {
    items: userGroupsPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;
    return (
      <div className={styles.component}>
        <div className={styles.okrHeader}>
          <UserGroupBar header />
        </div>
        <div className={styles.okrBars}>
          {items.map(group =>
            <div key={group.id} className={styles.okrBar}>
              <UserGroupBar group={group} />
            </div>)}
        </div>
      </div>);
  }
}
