import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { keyResultPropTypes } from '../propTypes';
import InlineTextInput from '../../../editors/InlineTextInput';
import InlineTextArea from '../../../editors/InlineTextArea';
import styles from './KRDetailsBar.css';

export default class KRDetailsBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    keyResult: keyResultPropTypes,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, keyResult } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.okr}>目標</div>
          <div className={styles.progress}>進捗</div>
          <div className={styles.owner}>担当者</div>
          <div className={styles.action}>アクション</div>
        </div>);
    }
    const { name, detail, unit, targetValue, achievedValue, achievementRate, owner } = keyResult;
    return (
      <div className={styles.component}>
        <div className={styles.name}>
          <InlineTextInput value={name} />
          <div className={styles.detail}><InlineTextArea value={detail} /></div>
        </div>
        <div className={styles.progressColumn}>
          <div className={styles.progressBox}>
            <div className={styles.progressPercent}>{achievementRate}%</div>
            <div className={styles.progressBar}>
              <div
                className={this.getProgressStyles(achievementRate)}
                style={{ width: `${achievementRate}%` }}
              />
            </div>
          </div>
          <div className={styles.progressConstituents}>
            {achievedValue}／{targetValue}{unit}
          </div>
        </div>
        <div className={styles.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
        <div className={styles.krCount}>
          <a className={styles.circle} href=""><img src="/img/common/inc_organization.png" alt="Organization" /></a>
          <a className={styles.circle} href=""><img src="/img/common/inc_link.png" alt="Link" /></a>
        </div>
      </div>);
  }
}
