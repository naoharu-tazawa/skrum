import React, { Component } from 'react';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberBar from './GroupMemberBar';
import styles from './GroupMemberList.css';

export default class GroupMemberList extends Component {

  static propTypes = {
    items: groupMembersPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;
    return (
      <section className={styles.member_list}>
        <h1 className={styles.ttl_setion}>メンバー一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <table>
            <thead>
              <GroupMemberBar header />
            </thead>
            <tbody>
              {items.map(member => <GroupMemberBar key={member.id} member={member} />)}
            </tbody>
            <tfoot>
              <tr>
                <td colSpan="4" >
                  <div className={`${styles.add_okr} ${styles.alignC}`}>
                    <a href=""><span className={styles.circle}><img src="/img/common/icn_plus.png" alt="Add" /></span><span>メンバーを追加</span></a>
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </section>);
  }
}
