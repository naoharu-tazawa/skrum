import React, { Component } from 'react';
import { timeframesPropTypes } from './propTypes';
import TimeframeBar from './TimeframeBar';
import styles from './TimeframeList.css';

export default class TimeframeList extends Component {

  static propTypes = {
    items: timeframesPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;
    return (
      <section className={styles.group_list}>
        <h1 className={styles.ttl_setion}>タイムフレーム一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <table>
            <thead>
              <TimeframeBar header />
            </thead>
            <tbody>
              {items.map(timeframe => <TimeframeBar key={timeframe.id} timeframe={timeframe} />)}
            </tbody>
            <tfoot>
              <tr>
                <td colSpan="5" >
                  <div className={`${styles.add_okr} ${styles.alignC}`}>
                    <a href=""><span className={styles.circle}><img src="/img/common/icn_plus.png" alt="Add" /></span><span>新しいタイムフレームを追加</span></a>
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </section>);
  }
}
