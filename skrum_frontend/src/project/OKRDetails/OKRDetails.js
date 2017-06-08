import React, { Component } from 'react';
import { okrPropTypes } from './propTypes';
import InlineTextInput from '../../editors/InlineTextInput';
import InlineTextArea from '../../editors/InlineTextArea';
import styles from './OKRDetails.css';

export default class OKRDetails extends Component {

  static propTypes = {
    okr: okrPropTypes,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { okr } = this.props;
    const { name, detail, unit, targetValue, achievedValue, achievementRate, owner } = okr;
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
