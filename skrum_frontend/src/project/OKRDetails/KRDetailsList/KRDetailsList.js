import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { keyResultsPropTypes } from '../propTypes';
import KRDetailsBar from './KRDetailsBar';
import styles from './KRDetailsList.css';

export default class KRDetailsList extends Component {

  static propTypes = {
    keyResults: keyResultsPropTypes,
    onAdd: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
  };

  render() {
    const { keyResults = [], onAdd, dispatchPutOKR, dispatchDeleteKR } = this.props;
    return (
      <div className={styles.component}>
        <div className={styles.header}>
          <KRDetailsBar header />
        </div>
        <div className={styles.bars}>
          {keyResults.map(keyResult =>
            <KRDetailsBar
              key={keyResult.id}
              {...{ keyResult, dispatchPutOKR, dispatchDeleteKR }}
            />)}
        </div>
        <div className={`${styles.add_okr} ${styles.alignC}`}>
          <button className={styles.addOkr} onClick={onAdd}>
            <span className={styles.circle}>
              <img src="/img/common/icn_plus.png" alt="Add" />
            </span>
            <span>新しいサブ目標を追加</span>
          </button>
        </div>
      </div>);
  }
}
