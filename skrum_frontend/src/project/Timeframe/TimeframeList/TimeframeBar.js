import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { timeframePropTypes } from './propTypes';
import InlineTextInput from '../../../editors/InlineTextInput';
import styles from './TimeframeBar.css';

export default class TimeframeBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    timeframe: timeframePropTypes,
    dispatchPutTimeframe: PropTypes.func,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, timeframe, dispatchPutTimeframe } = this.props;
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
    const { id, name, startDate, endDate, defaultFlg } = timeframe;
    return (
      <tr>
        <td>
          <div className={styles.td}>
            <InlineTextInput
              value={name}
              onSubmit={value => dispatchPutTimeframe(id, { timeframeName: value })}
            />
          </div>
        </td>
        <td><div>{startDate}</div></td>
        <td><div>{endDate}</div></td>
        <td>
          {defaultFlg === 1 ? <div className={styles.circle}><img className={styles.check} src="/img/check.svg" alt="" /></div> : <div className={styles.circle_inactive} />}
        </td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);
  }
}
