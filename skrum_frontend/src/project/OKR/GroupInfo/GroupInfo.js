import React, { Component } from 'react';
import { Link } from 'react-router';
import { groupPropTypes } from './propTypes';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import { replacePath } from '../../../util/RouteUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';
import styles from './GroupInfo.css';

export default class GroupInfo extends Component {

  static propTypes = {
    group: groupPropTypes.isRequired,
  };

  render() {
    const { group } = this.props;
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
        <figure className={styles.floatL}>
          <EntityLink entity={{ id: groupId, type: EntityType.GROUP }} fluid avatarOnly />
          <figcaption className={styles.alignC}>
            最終更新:<br />{toRelativeTimeText(lastUpdate)}
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
            <EntityLink
              className={styles.leader_info}
              entity={leaderUserId && { id: leaderUserId, name: leaderName, type: EntityType.USER }}
              title="リーダー"
            />
            <Link
              className={`${styles.btn} ${styles.btn_arrow_r}`}
              to={replacePath({ tab: 'control' })}
            >
              メンバー一覧
            </Link>
          </div>
        </div>
      </div>
    );
  }
}
