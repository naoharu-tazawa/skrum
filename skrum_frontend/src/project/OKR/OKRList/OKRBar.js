import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import _ from 'lodash';
import { okrPropTypes, keyResultPropTypes } from './propTypes';
import { replacePath } from '../../../util/RouteUtil';
import styles from './OKRBar.css';

const gap = 1;
const imageDim = 2.25;
const progressBarWidth = 10;
const ownerBoxWidth = 10;

const colStyle = {
  mapImage: {
    minWidth: `${imageDim}em`,
//    height: `${imageDim}em`,
  },
  name: {
    width: '100%',
  },
  progressBar: {
    minWidth: `${progressBarWidth}em`,
  },
  ownerBox: {
    minWidth: `${ownerBoxWidth}em`,
  },
  krCount: {
    minWidth: `${imageDim}em`,
    textAlign: 'right',
  },
};

export default class OKRBar extends Component {

  static propTypes = {
    display: PropTypes.oneOf(['o-header', 'kr-header', 'normal', 'full', 'expanded', 'collapsed']).isRequired,
    okr: okrPropTypes,
    keyResult: keyResultPropTypes,
  };

  getBaseStyles = (detail) => {
    const { display } = this.props;
    const baseStyles = [
      styles.component,
      ...[detail ? [styles.detailed] : []],
      ...[display === 'full' ? [styles.full] : [styles.normal]],
      ...[display === 'expanded' || display === 'collapsed' ? [styles.keyResult] : []],
      ...[display === 'collapsed' ? [styles.collapsed] : []],
    ];
    return _.join(baseStyles, ' ');
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { display, okr, keyResult } = this.props;
    if (display === 'o-header' || display === 'kr-header') {
      return (
        <div className={styles.header}>
          <div style={{ ...colStyle.mapImage, margin: `auto ${gap / 2}em` }} />
          <div style={{ ...colStyle.name, margin: 'auto 0' }}>OKR</div>
          <div style={{ ...colStyle.progressBar, margin: `auto ${gap}em auto 0` }}>進捗</div>
          <div style={{ ...colStyle.ownerBox, margin: 'auto 0' }}>所有者</div>
          <div style={{ ...colStyle.krCount, margin: `auto ${gap}em auto 0` }}>{display === 'o-header' ? 'KR' : ''}</div>
        </div>);
    }
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate,
      owner, keyResults } = keyResult || okr;
    return (
      <div className={this.getBaseStyles(detail)}>
        <div className={styles.mapImage} style={colStyle.mapImage} />
        <div className={styles.name} style={colStyle.name}>
          <div>
            {keyResult && okr ? <span className={styles.keyResultConnector}>└</span> : null}
            {keyResult || display === 'full' ? name : (
              <Link
                to={replacePath({ aspect: 'o', aspectId: `${id}` })}
                className={styles.detailsLink}
                onClick={e => e.stopPropagation()}
              >
                {name}
              </Link>
            )}
          </div>
          {detail ? <div className={styles.detail}>{detail}</div> : null}
        </div>
        <div className={styles.progressColumn}>
          <div className={styles.progressBox} style={colStyle.progressBar}>
            <div className={styles.progressPercent}>{achievementRate}%</div>
            <div className={styles.progressBar}>
              <div
                className={this.getProgressStyles(achievementRate)}
                style={{ width: `${achievementRate}%` }}
              />
            </div>
          </div>
          <div className={styles.progressConstituents}>
            {achievedValue}{unit}／{targetValue}{unit}
          </div>
        </div>
        <div className={styles.ownerBox} style={colStyle.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
        <div className={styles.krCount} style={colStyle.krCount}>
          {keyResults && display !== 'full' ? keyResults.length : ''}
        </div>
      </div>);
  }
}
