import React, { Component } from 'react';
import { keyResultsPropTypes } from '../propTypes';
import KRDetailsBar from './KRDetailsBar';
import styles from './KRDetailsList.css';

export default class KRDetailsList extends Component {

  static propTypes = {
    keyResults: keyResultsPropTypes,
  };

  render() {
    const { keyResults = [] } = this.props;
    return (
      <div className={styles.component}>
        <div className={styles.header}>
          <KRDetailsBar header />
        </div>
        <div className={styles.bars}>
          {keyResults.map(keyResult => <KRDetailsBar key={keyResult.id} keyResult={keyResult} />)}
        </div>
        <div className={`${styles.add_okr} ${styles.alignC}`}>
          <a href=""><span className={styles.circle}><img src="/img/common/icn_plus.png" alt="Add" /></span><span>新しいサブ目標を追加</span></a>
        </div>
      </div>);
  }
}
