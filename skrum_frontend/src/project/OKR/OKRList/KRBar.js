import React, { Component } from 'react';
import PropTypes from 'prop-types';
import _ from 'lodash';
import { keyResultPropTypes } from './propTypes';
import styles from './KRBar.css';

export default class KRBar extends Component {

  static propTypes = {
    display: PropTypes.oneOf(['expanded', 'collapsed']).isRequired,
    keyResult: keyResultPropTypes.isRequired,
  };

  getBaseStyles = (display) => {
    const baseStyles = [
      styles.component,
      ...[display === 'collapsed' ? [styles.collapsed] : []],
    ];
    return _.join(baseStyles, ' ');
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { display, keyResult } = this.props;
    const { name, unit, targetValue, achievedValue, achievementRate, owner } = keyResult;
    return (
      <div className={this.getBaseStyles(display)}>
        <div className={styles.name}>
          {name}
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
