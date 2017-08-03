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
            最終更新: {toRelativeTimeText(lastUpdate)}
          </figcaption>
        </figure>
        <div className={`${styles.boxInfo} ${styles.floatR}`}>
          <p className={styles.ttl_user}>{lastName} {firstName}</p>
          <div className={styles.groups}>
            {groupsLink}
          </div>
          <div className={styles.infoList}>
            <table className={`${styles.user_info} ${styles.floatL}`}>
              <tbody>
                <tr>
                  <td><div className={styles.info}>役　職:</div></td>
                  <td><div className={styles.info_data}>{position}</div></td>
                </tr>
                <tr>
                  <td><div className={styles.info}>電　話:</div></td>
                  <td><div className={styles.info_data}>{phoneNumber}</div></td>
                </tr>
                <tr>
                  <td><div className={styles.info}>メール:</div></td>
                  <td><div className={styles.info_data}>{emailAddress}</div></td>
                </tr>
              </tbody>
            </table>
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
