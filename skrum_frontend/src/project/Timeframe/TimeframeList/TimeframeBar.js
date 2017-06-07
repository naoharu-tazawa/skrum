import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { timeframePropTypes } from './propTypes';
import styles from './TimeframeBar.css';

export default class TimeframeBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    timeframe: timeframePropTypes,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, timeframe } = this.props;
    if (header) {
      return (
        <tr>
          <th className={styles.name}>名前</th>
          <th className={styles.date}>開始日</th>
          <th className={styles.date}>終了日</th>
          <th className={styles.default}>デフォルト設定</th>
          <th />
        </tr>);
    }
    const { name, startDate, endDate, defaultFlg } = timeframe;
    return (
      <tr>
        <td><div className={styles.td}>{name}</div></td>
        <td><div className={styles.td}>{startDate}</div></td>
        <td><div className={styles.td}>{endDate}</div></td>
        <td>
          {defaultFlg === 1 ? <div className={styles.check}><img src="/img/check.svg" alt="" /></div> : <div className={styles.hyphen}>-</div>}
        </td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);
  }
}
