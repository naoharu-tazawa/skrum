import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { okrPropTypes, keyResultsPropTypes } from '../propTypes';
import KRDetailsBar from './KRDetailsBar';
import styles from './KRDetailsList.css';

export default class KRDetailsList extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    keyResults: keyResultsPropTypes,
    subject: PropTypes.string.isRequired,
    onAdd: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeKROwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
  };

  render() {
    const { parentOkr, keyResults = [], subject, onAdd, dispatchPutOKR, dispatchChangeKROwner,
      dispatchChangeParentOkr, dispatchChangeDisclosureType, dispatchDeleteKR } = this.props;
    return (
      <div className={styles.component}>
        <div className={styles.header}>
          <KRDetailsBar header />
        </div>
        <div className={styles.bars}>
          {keyResults.map(keyResult =>
            <KRDetailsBar
              key={`kr-details-${keyResult.id}`}
              {...{
                parentOkr,
                keyResult,
                subject,
                dispatchPutOKR,
                dispatchChangeKROwner,
                dispatchChangeParentOkr,
                dispatchChangeDisclosureType,
                dispatchDeleteKR,
              }}
            />)}
        </div>
        <div className={`${styles.footer} ${styles.alignC}`}>
          <button className={styles.addKR} onClick={onAdd}>
            <span className={styles.circle}>
              <img src="/img/common/icn_plus.png" alt="Add" />
            </span>
            <span>新しいサブ目標を追加</span>
          </button>
        </div>
      </div>);
  }
}
