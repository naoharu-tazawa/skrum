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
      <section className={styles.group_list}>
        <h1 className={styles.ttl_setion}>所属グループ一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <table>
            <thead>
              <UserGroupBar header />
            </thead>
            <tbody>
              {items.map(group => <UserGroupBar key={group.id} group={group} />)}
            </tbody>
          </table>
        </div>
      </section>);
  }
}
