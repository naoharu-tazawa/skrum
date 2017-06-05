import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import _ from 'lodash';
import { okrPropTypes, keyResultPropTypes } from './propTypes';
import { replacePath } from '../../../util/RouteUtil';
import styles from './OKRBar.css';

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
          <div className={styles.okr}>目標</div>
          <div className={styles.progress}>進捗</div>
          <div className={styles.owner}>担当者</div>
          <div className={styles.action}>{display === 'o-header' ? 'アクション' : ''}</div>
        </div>);
    }
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate,
      owner, keyResults } = keyResult || okr;
    return (
      <div className={this.getBaseStyles(detail)}>
        <div className={styles.name}>
          <div>
            {keyResult && okr ? <span className={styles.keyResultConnector}><img src="/img/common/inc_sub_nav.png" alt="" /></span> : null}
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
          {keyResults && display !== 'full' ? <div className={`${styles.circle} ${styles.circle_small} ${styles.circle_plus}`}>＋{keyResults.length}</div> : ''}
        </div>
      </div>);
  }
}
