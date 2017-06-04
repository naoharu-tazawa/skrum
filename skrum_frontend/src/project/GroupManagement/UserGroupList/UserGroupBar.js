import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { userGroupPropTypes } from './propTypes';
import styles from './UserGroupBar.css';

export default class UserGroupBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    group: userGroupPropTypes,
  };

  render() {
    const { header, group } = this.props;
    if (header) {
      return (
        <tr>
          <th className={styles.name}>名前</th>
          <th className={styles.position}>進捗状況</th>
        </tr>);
    }
    const { name, achievementRate } = group;
    return (
      <tr>
        <td><span><img src="/img/profile/img_leader.jpg" alt="" /></span>{name}</td>
        <td>
          <div className={styles.progressBox}>
            <span className={styles.progressPercent}>{achievementRate}%</span>
            <progress className={styles.progressBar} max={100} value={achievementRate} />
          </div>
        </td>
      </tr>);
  }
}
