import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes } from './propTypes';
import EntityLink from '../../../components/EntityLink';
import { replacePath } from '../../../util/RouteUtil';
import styles from './OkrBar.css';

export default class OkrBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    okr: okrPropTypes,
    onKRClicked: PropTypes.func,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, okr, onKRClicked } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.okr}>目標</div>
          <div className={styles.progress}>進捗</div>
          <div className={styles.owner}>担当者</div>
          <div className={styles.action}>アクション</div>
        </div>);
    }
    const { id, name, unit, targetValue, achievedValue, achievementRate,
      owner, keyResults } = okr;
    return (
      <div className={styles.component}>
        <div className={styles.name}>
          <Link
            to={replacePath({ aspect: 'o', aspectId: `${id}` })}
            onMouseUp={e => e.stopPropagation()}
          >
            {name}
          </Link>
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
        <EntityLink componentClassName={styles.ownerBox} entity={owner} />
        <div className={styles.krCount}>
          <a className={styles.circle} href=""><img src="/img/common/inc_organization.png" alt="Organization" /></a>
          <a className={styles.circle} href=""><img src="/img/common/inc_link.png" alt="Link" /></a>
          {keyResults && (
            <a
              className={`${styles.circle} ${styles.circle_small} ${styles.circle_plus}`}
              onClick={onKRClicked}
              tabIndex={0}
            >
              ＋{keyResults.length}
            </a>)}
        </div>
      </div>);
  }
}
