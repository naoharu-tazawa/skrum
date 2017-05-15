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
      <div className={styles.component}>
        <div className={styles.okrHeader}>
          <GroupMemberBar header />
        </div>
        <div className={styles.okrBars}>
          {items.map(member =>
            <div key={member.id} className={styles.okrBar}>
              <GroupMemberBar member={member} />
            </div>)}
        </div>
      </div>);
  }
}
