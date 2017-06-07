import React, { Component } from 'react';
import { groupsPropTypes } from './propTypes';
import GroupBar from './GroupBar';
import styles from './GroupList.css';

export default class GroupList extends Component {

  static propTypes = {
    items: groupsPropTypes.isRequired,
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
              <GroupBar header />
            </thead>
            <tbody>
              {items.map(user => <GroupBar key={user.id} user={user} />)}
            </tbody>
          </table>
        </div>
      </section>);
  }
}
