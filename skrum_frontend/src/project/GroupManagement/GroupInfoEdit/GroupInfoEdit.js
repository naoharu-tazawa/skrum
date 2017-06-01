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
    const { group } = this.props;
    const { name, /* company, dept, */ mission, leaderName, lastUpdate } = group;
    return (
      <section className={styles.profile_box}>
        <h1 className={styles.ttl_setion}>基本情報</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <div className={styles.profile_img}>
            <div><img src="/img/profile/img_profile.jpg" alt="" /></div>
            <p>最終更新: {convertToRelativeTimeText(lastUpdate)}</p>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.team_name}>{name}</h2>
            <div className={styles.member_tree}>
              <span>Company Name</span>
              <span>Group Name</span>
              <span>Group Name</span>
              <span>Group Name</span>
            </div>
            <p>{mission}</p>
            <div className={`${styles.leader} ${styles.cf}`}>
              <div><img src="/img/profile/img_leader.jpg" alt="" /></div>
              <dl>
                <dt>リーダー</dt>
                <dd>{leaderName}</dd>
              </dl>
              <button className={styles.hover}><img src="/img/profile/icn_write.png" alt="" width="39" /></button>
            </div>
          </div>
        </div>
      </section>
    );
  }
}
