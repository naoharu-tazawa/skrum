import React, { Component } from 'react';
import { usersPropTypes } from './propTypes';
import UserBar from './UserBar';
import styles from './UserList.css';

export default class UserList extends Component {

  static propTypes = {
    items: usersPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;
    return (
      <section className={styles.member_list}>
        <div className={styles.title}>ユーザ検索</div>
        <div><input className={styles.input} type="text" /></div>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <table>
            <thead>
              <UserBar header />
            </thead>
            <tbody>
              {items.map(user => <UserBar key={user.id} user={user} />)}
            </tbody>
          </table>
        </div>
      </section>);
  }
}
