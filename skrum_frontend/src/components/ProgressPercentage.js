import React, { Component } from 'react';
import PropTypes from 'prop-types';
import styles from './ProgressPercentage.css';

export default class ProgressPercentage extends Component {

  static propTypes = {
    unit: PropTypes.string,
    targetValue: PropTypes.number,
    achievedValue: PropTypes.number,
    achievementRate: PropTypes.string.isRequired,
    fluid: PropTypes.bool,
    componentClassName: PropTypes.string,
    children: PropTypes.node,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { unit = '％', targetValue, achievedValue = 0, achievementRate, fluid,
      componentClassName, children } = this.props;
    const hasConstituents = targetValue !== 100 || (unit !== '%' && unit !== '％');
    return (
      <div className={componentClassName}>
        <div className={`${styles.progressBox} ${fluid && styles.fluid}`}>
          <div className={styles.progressPercent}>
            {achievementRate}%
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
                {achievedValue}／{targetValue}{unit}
              </div>)}
            {children}
          </div>
        </div>
      </div>);
  }
}
