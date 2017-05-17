import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupPropTypes } from './propTypes';
import styles from './GroupInfoEdit.css';
import { convertToRelativeTimeText } from '../../../util/DatetimeUtil';

export default class GroupInfoEdit extends Component {

  static propTypes = {
    group: groupPropTypes.isRequired,
    infoLink: PropTypes.string.isRequired,
  };

  render() {
    const { group, infoLink } = this.props;
    const { name, /* company, dept, */ mission, leaderName, lastUpdate } = group;
    return (
      <div className={styles.component}>
        <div className={styles.groupBox}>
          <div className={styles.groupImage} />
          <div className={styles.lastUpdate}>最終更新: {convertToRelativeTimeText(lastUpdate)}</div>
        </div>
        <div className={styles.groupInfo}>
          <div className={styles.groupName}>{name}</div>
          {/* <div className={styles.groupDept}>{company} > {dept}</div> */}
          <div className={styles.groupGoal}>{mission}</div>
          <div className={styles.groupPart}>
            <div className={styles.groupLeader}>
              <div>リーダー</div>
              <div>{leaderName}</div>
            </div>
            <a className={styles.moreLink} href={infoLink}>メンバー一覧 ➔</a>
          </div>
        </div>
      </div>
    );
  }
}
