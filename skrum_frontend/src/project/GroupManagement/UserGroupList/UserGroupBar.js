import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { userGroupPropTypes } from './propTypes';
import styles from './UserGroupBar.css';

export default class UserGroupBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    group: userGroupPropTypes,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, group } = this.props;
    if (header) {
      return (
        <tr>
          <th className={styles.name}>名前</th>
          <th className={styles.position}>進捗状況</th>
          <th />
        </tr>);
    }
    const { name, achievementRate } = group;
    return (
      <tr>
        <td><span><img src="/img/profile/img_leader.jpg" alt="" /></span>{name}</td>
        <td>
          <div className={styles.progressBox}>
            <span className={styles.progressPercent}>{achievementRate}%</span>
            <div className={styles.progressBar}>
              <div
                className={this.getProgressStyles(achievementRate)}
                style={{ width: `${achievementRate}%` }}
              />
            </div>
          </div>
        </td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);
  }
}
