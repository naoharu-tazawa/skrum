import React, { Component } from 'react';
import PropTypes from 'prop-types';
import _ from 'lodash';
import { userPropTypes } from './propTypes';
import styles from './UserInfo.css';
import { convertToRelativeTimeText } from '../../util/DatetimeUtil';

export default class UserInfo extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
    infoLink: PropTypes.string,
  };

  render() {
    const { user, infoLink } = this.props;
    const { name, departments, position, phoneNumber, emailAddress, lastUpdate } = user;
    return (
      <div className={styles.component}>
        <div className={styles.userBox}>
          <div className={styles.userImage} />
          <div className={styles.lastUpdate}>最終更新: {convertToRelativeTimeText(lastUpdate)}</div>
        </div>
        <div className={styles.userInfo}>
          <div className={styles.userName}>{name}</div>
          <div className={styles.userDept}>所属部署: {(_.head(departments) || {}).groupName}</div>
          <table>
            <tbody>
              <tr className={styles.userRank}>
                <td className={styles.userLabel}>役 職:</td><td>{position}</td>
              </tr>
              <tr className={styles.userPart}>
                <td className={styles.userLabel}>Tel:</td><td>{phoneNumber}</td>
              </tr>
              <tr className={styles.userPart}>
                <td className={styles.userLabel}>Email:</td><td>{emailAddress}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <a className={styles.moreLink} href={infoLink}>詳細 ➔</a>
      </div>);
  }
}
