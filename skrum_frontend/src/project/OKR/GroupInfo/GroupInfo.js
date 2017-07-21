import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { groupPropTypes } from './propTypes';
import styles from './GroupInfo.css';
import InlinePotentialLeaders from '../../PotentialLeaders/InlinePotentialLeaders';
import { replacePath } from '../../../util/RouteUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';

export default class GroupInfo extends Component {

  static propTypes = {
    group: groupPropTypes.isRequired,
    infoLink: PropTypes.string.isRequired,
    dispatchChangeGroupLeader: PropTypes.func.isRequired,
  };

  render() {
    const { group, infoLink, dispatchChangeGroupLeader } = this.props;
    const { groupId, name, groupPaths, mission, leaderUserId, leaderName, lastUpdate } = group;
    const groupPathsLink = groupPaths.map(({ groupTreeId, groupPath }) =>
      <ul key={groupTreeId}>
        {groupPath.map(({ id, name: groupName }, index) =>
          <li key={id}>
            <Link
              to={replacePath({ subject: !index ? 'company' : 'group', id })}
              className={styles.groupLink}
            >
              {groupName}
            </Link>
          </li>)}
      </ul>);
    return (
      <div className={`${styles.content} ${styles.cf}`}>
        <figure className={`${styles.avatar} ${styles.floatL}`}>
          <img src="/img/time_management/icn_team.png" alt="Team Name" />
          <figcaption className={styles.alignC}>
            最終更新: {toRelativeTimeText(lastUpdate)}
          </figcaption>
        </figure>
        <div className={`${styles.boxInfo} ${styles.floatR}`}>
          <p className={styles.ttl_team}>{name}</p>
          <nav className={styles.breads}>
            {groupPathsLink}
          </nav>
          <div>
            <div className={styles.title}>ミッション</div>
            <div className={styles.txt}>{mission}</div>
          </div>
          <div className={`${styles.nav_info} ${styles.cf}`}>
            <div className={`${styles.leader_info} ${styles.user_info} ${styles.floatL} ${styles.cf}`}>
              <div className={`${styles.avatar} ${styles.floatL}`}><img src="/img/common/icn_user.png" alt="User Name" /></div>
              <div className={`${styles.info} ${styles.floatL}`}>
                <p className={styles.ttl_user}>リーダー</p>
                <div className={styles.user_name}>
                  <InlinePotentialLeaders
                    groupId={groupId}
                    leaderUserId={leaderUserId}
                    leaderName={leaderName}
                    onSubmit={({ userId, name: userName }, completion) =>
                      dispatchChangeGroupLeader(userId, userName).then(completion)}
                  />
                </div>
              </div>
            </div>
            <a className={`${styles.btn} ${styles.btn_arrow_r}`} href={infoLink}>メンバー一覧</a>
          </div>
        </div>
      </div>
    );
  }
}
