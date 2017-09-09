import React, { Component } from 'react';
import { Link } from 'react-router';
import { userPropTypes } from './propTypes';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import { replacePath } from '../../../util/RouteUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';
import styles from './UserInfo.css';

export default class UserInfo extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
  };

  render() {
    const { user } = this.props;
    const { userId, lastName, firstName, departments, position,
      phoneNumber, emailAddress, lastUpdate } = user;
    const groupsLink = departments.map(({ groupId, groupName }, index) =>
      <span key={groupId}>
        {index ? '・' : ''}
        <Link to={replacePath({ subject: 'group', id: groupId })} className={styles.groupLink}>{groupName}</Link>
      </span>);
    return (
      <div className={`${styles.content} ${styles.cf}`}>
        <figure className={styles.floatL}>
          <EntityLink entity={{ id: userId, type: EntityType.USER }} fluid avatarOnly />
          <figcaption className={styles.alignC}>
            最終更新:<br />{toRelativeTimeText(lastUpdate)}
          </figcaption>
        </figure>
        <div className={`${styles.boxInfo} ${styles.floatR}`}>
          <p className={styles.ttl_user}>{lastName} {firstName}</p>
          <div className={styles.groups}>
            {groupsLink}
          </div>
          <div className={styles.infoList}>
            <div className={`${styles.user_info} ${styles.floatL}`}>
              <div className={styles.info}>
                <small>役　職:</small>
                <span className={styles.info_data}>{position}</span>
              </div>
              <div className={styles.info}>
                <small>電　話:</small>
                <span className={styles.info_data}>{phoneNumber}</span>
              </div>
              <div className={styles.info}>
                <small>メール:</small>
                <span className={styles.info_data}>{emailAddress}</span>
              </div>
            </div>
            <Link
              className={`${styles.btn} ${styles.btn_arrow_r}`}
              to={replacePath({ tab: 'control' })}
            >
              グループ一覧
            </Link>
          </div>
        </div>
      </div>);
  }
}
