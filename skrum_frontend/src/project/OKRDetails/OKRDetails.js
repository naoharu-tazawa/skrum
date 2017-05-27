import React, { Component } from 'react';
import { okrPropTypes } from '../OKR/OKRList/propTypes';
import styles from './OKRDetails.css';

export default class OKRDetails extends Component {

  static propTypes = {
    details: okrPropTypes.isRequired,
  };

  getBaseStyles = () =>
    '';

  render() {
    const { details } = this.props;
    const { name, achievementRate, owner } = details;
    return (
      <div className={this.getBaseStyles()}>
        <div className={styles.mapImage} />
        <div className={styles.name}>
          <span>{name}</span>
        </div>
        <div className={styles.progressBox}>
          <div className={styles.progressPercent}>{achievementRate}%</div>
          <progress className={styles.progressBar} max={100} value={achievementRate} />
        </div>
        <div className={styles.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
      </div>);
  }
}
