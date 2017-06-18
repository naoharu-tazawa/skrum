import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberBar from './GroupMemberBar';
import styles from './GroupMemberList.css';

export default class GroupMemberList extends Component {

  static propTypes = {
    items: groupMembersPropTypes.isRequired,
    roleLevel: PropTypes.number.isRequired,
  };

  render() {
    const { items, roleLevel } = this.props;
    return (
      <section className={styles.member_list}>
        <h1 className={styles.ttl_setion}>メンバー一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <table>
            <thead>
              <GroupMemberBar header roleLevel={roleLevel} />
            </thead>
            <tbody>
              {items.map(member =>
                <GroupMemberBar key={member.id} member={member} roleLevel={roleLevel} />)}
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
