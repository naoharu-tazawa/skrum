import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes } from './propTypes';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntityLink from '../../../components/EntityLink';
import DropdownMenu from '../../../components/DropdownMenu';
import { replacePath } from '../../../util/RouteUtil';
import styles from './OkrBar.css';

export default class OkrBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    okr: okrPropTypes,
    onKRClicked: PropTypes.func,
    onAddParentedOkr: PropTypes.func,
  };

  render() {
    const { header, okr, onKRClicked, onAddParentedOkr } = this.props;
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
            to={replacePath({ aspect: 'o', aspectId: id })}
            onMouseUp={e => e.stopPropagation()}
          >
            {name}
          </Link>
        </div>
        <ProgressPercentage
          componentClassName={styles.progressColumn}
          {...{ unit, targetValue, achievedValue, achievementRate }}
        />
        <EntityLink componentClassName={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          <Link
            className={styles.circle}
            to={replacePath({ tab: 'map', aspect: 'o', aspectId: id })}
          >
            <img src="/img/common/inc_organization.png" alt="Map" />
          </Link>
          <DropdownMenu
            options={[
              { caption: 'この目標に紐付ける', onClick: () => onAddParentedOkr(okr) },
            ]}
          />
          {keyResults && (
            <a
              className={`${styles.circle} ${styles.circle_small} ${styles.circle_plus} ${keyResults.length === 0 && styles.invisible}`}
              onClick={onKRClicked}
              tabIndex={0}
            >
              ＋{keyResults.length}
            </a>)}
        </div>
      </div>);
  }
}
