import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { groupMemberPropTypes } from './propTypes';
import { replacePath } from '../../../util/RouteUtil';
import styles from './GroupMemberBar.css';

export default class GroupMemberBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    member: groupMemberPropTypes,
    roleLevel: PropTypes.number.isRequired,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, member, roleLevel } = this.props;

    const getHeader = () => (
      <tr>
        <th className={styles.name}>名前</th>
        <th className={styles.position}>役職</th>
        <th className={styles.update}>最終ログイン</th>
        <th />
      </tr>);

    const getHeaderAdmin = () => (
      <tr>
        <th className={styles.nameAdmin}>名前</th>
        <th className={styles.positionAdmin}>役職</th>
        <th className={styles.progressAdmin}>進捗状況</th>
        <th className={styles.updateAdmin}>最終ログイン</th>
        <th />
      </tr>);

    if (header) {
      const retHeader = roleLevel < 4 ? getHeader() : getHeaderAdmin();
      return retHeader;
    }

    const { id, name, position, achievementRate, lastLogin } = member;

    const getRow = () => (
      <tr>
        <td>
          <span>
            <img src="/img/profile/img_leader.jpg" alt="" />
          </span>
          <span key={id}>
            <Link to={replacePath({ subject: 'user', id })}>{name}</Link>
          </span>
        </td>
        <td>{position}</td>
        <td>{lastLogin}</td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);

    const getRowAdmin = () => (
      <tr>
        <td>
          <span>
            <img src="/img/profile/img_leader.jpg" alt="" />
          </span>
          <span key={id}>
            <Link to={replacePath({ subject: 'user', id })}>{name}</Link>
          </span>
        </td>
        <td>{position}</td>
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
        <td>{lastLogin}</td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);

    const retRow = roleLevel < 4 ? getRow() : getRowAdmin();
    return retRow;
  }
}
