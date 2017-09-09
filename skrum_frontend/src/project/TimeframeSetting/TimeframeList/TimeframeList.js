import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { timeframesPropTypes } from './propTypes';
import TimeframeBar from './TimeframeBar';
import NewTimeframe from './NewTimeframe';
import { withModal } from '../../../util/ModalUtil';
import styles from './TimeframeList.css';

class TimeframeList extends Component {

  static propTypes = {
    items: timeframesPropTypes.isRequired,
    dispatchPutTimeframe: PropTypes.func.isRequired,
    dispatchDefaultTimeframe: PropTypes.func.isRequired,
    dispatchDeleteTimeframe: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { items, dispatchPutTimeframe, dispatchDefaultTimeframe, dispatchDeleteTimeframe,
      openModal } = this.props;
    return (
      <section className={styles.group_list}>
        <h1 className={styles.ttl_setion}>タイムフレーム一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <TimeframeBar header />
          {items.map(timeframe =>
            <TimeframeBar
              key={timeframe.id}
              timeframe={timeframe}
              {...{ dispatchPutTimeframe, dispatchDefaultTimeframe, dispatchDeleteTimeframe }}
            />)}
          <div className={`${styles.footer} ${styles.alignC}`}>
            <button
              className={styles.addTimeframe}
              onClick={() => openModal(NewTimeframe)}
            >
              <span className={styles.circle}>
                <img src="/img/common/icn_plus.png" alt="Add" />
              </span>
              <span>新しいタイムフレームを追加</span>
            </button>
          </div>
        </div>
      </section>);
  }
}

export default withModal(TimeframeList);
