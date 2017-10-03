import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { floor } from 'lodash';
import styles from './ProgressPercentage.css';

export default class ProgressPercentage extends Component {

  static propTypes = {
    unit: PropTypes.string,
    targetValue: PropTypes.number,
    achievedValue: PropTypes.number,
    achievementRate: PropTypes.number.isRequired,
    fluid: PropTypes.bool,
    className: PropTypes.string,
    children: PropTypes.node,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { unit = '％', targetValue, achievedValue = 0, achievementRate, fluid,
      className, children } = this.props;
    const hasConstituents = (targetValue !== undefined) && (targetValue !== 100 || (unit !== '%' && unit !== '％'));
    return (
      <div className={className}>
        <div className={`${styles.progressBox} ${fluid && styles.fluid}`}>
          <div className={styles.progressPercent}>
            {floor(achievementRate)}%
          </div>
          <div className={styles.progressDetails}>
            <div className={`${styles.progressBar} ${(hasConstituents || children) && styles.spaced}`}>
              <div
                className={this.getProgressStyles(achievementRate)}
                style={{ width: `${achievementRate}%` }}
              />
            </div>
            {hasConstituents && (
              <div className={styles.progressConstituents}>
                {achievedValue.toLocaleString()}／{targetValue.toLocaleString()}{unit}
              </div>)}
            {children}
          </div>
        </div>
      </div>);
  }
}
