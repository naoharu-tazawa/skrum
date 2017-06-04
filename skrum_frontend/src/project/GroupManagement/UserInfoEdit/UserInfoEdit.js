import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { userPropTypes } from './propTypes';
import styles from './UserInfoEdit.css';
import { replacePath } from '../../../util/RouteUtil';
import { convertToRelativeTimeText } from '../../../util/DatetimeUtil';

export default class UserInfoEdit extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
    infoLink: PropTypes.string,
  };

  render() {
    const { user } = this.props;
    const { name, departments, position, phoneNumber, emailAddress, lastUpdate } = user;
    const groupsLink = departments.map(({ groupId, groupName }, index) =>
      <span key={groupId}>
        {index ? '・' : ''}
        <Link to={replacePath({ subject: 'group', id: groupId })} className={styles.groupLink}>{groupName}</Link>
      </span>);
    return (
      <section className={styles.profile_box}>
        <h1 className={styles.ttl_setion}>基本情報</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <div className={styles.profile_img}>
            <div><img src="/img/profile/img_profile.jpg" alt="" /></div>
            <p>最終更新: {convertToRelativeTimeText(lastUpdate)}</p>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.user_name}>{name}</h2>
            <div className={styles.groups}>
              {groupsLink}
            </div>
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
          </div>
          <button className={styles.hover}><img src="/img/profile/icn_write.png" alt="" width="39" /></button>
        </div>
      </section>);
  }
}
