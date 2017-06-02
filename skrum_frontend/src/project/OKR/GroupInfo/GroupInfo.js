import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { groupPropTypes } from './propTypes';
import styles from './GroupInfo.css';
import { replacePath } from '../../../util/RouteUtil';
import { convertToRelativeTimeText } from '../../../util/DatetimeUtil';

export default class GroupInfo extends Component {

  static propTypes = {
    group: groupPropTypes.isRequired,
    infoLink: PropTypes.string.isRequired,
  };

  render() {
    const { group, infoLink } = this.props;
    const { name, groupPaths, mission, leaderName, lastUpdate } = group;
    const groupPathsLink = groupPaths.map(({ groupTreeId, groupPath }) =>
      <div key={groupTreeId} className={styles.groupTree}>
        {groupPath.map(({ id, name: groupName }, index) =>
          <span key={id}>
            {index ? '／' : ''}
            <Link
              to={replacePath({ subject: !index ? 'company' : 'group', id })}
              className={styles.groupLink}
            >
              {groupName}
            </Link>
          </span>)}
      </div>);
    return (
      <div className={styles.component}>
        <div className={styles.groupBox}>
          <div className={styles.groupImage} />
          <div className={styles.lastUpdate}>最終更新: {convertToRelativeTimeText(lastUpdate)}</div>
        </div>
        <div className={styles.groupInfo}>
          <div className={styles.groupName}>{name}</div>
          <div className={styles.groupDept}>{groupPathsLink}</div>
          <div className={styles.groupGoal}>{mission}</div>
          <div className={styles.groupPart}>
            <div className={styles.groupLeader}>
              <div>リーダー</div>
              <div>{leaderName}</div>
            </div>
            <a className={styles.moreLink} href={infoLink}>メンバー一覧 ➔</a>
          </div>
        </div>
      </div>
    );
  }
}
