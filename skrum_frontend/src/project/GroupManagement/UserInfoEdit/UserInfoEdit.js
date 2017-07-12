import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { userPropTypes } from './propTypes';
import styles from './UserInfoEdit.css';
import InlineTextInput from '../../../editors/InlineTextInput';
import { replacePath } from '../../../util/RouteUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';

export default class UserInfoEdit extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
    infoLink: PropTypes.string,
    dispatchPutUser: PropTypes.func.isRequired,
  };

  render() {
    const { user, dispatchPutUser } = this.props;
    const { userId, lastName, firstName, departments,
      position, phoneNumber, emailAddress, lastUpdate } = user;
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
            <p>最終更新: {toRelativeTimeText(lastUpdate)}</p>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.user_name}>
              <span className={styles.user_name_l}>
                <InlineTextInput
                  value={lastName}
                  onSubmit={(value, completion) =>
                    dispatchPutUser(userId, { lastName: value }).then(completion)}
                />
              </span>
              <span className={styles.user_name_f}>
                <InlineTextInput
                  value={firstName}
                  onSubmit={(value, completion) =>
                    dispatchPutUser(userId, { firstName: value }).then(completion)}
                />
              </span>
            </h2>
            <div className={styles.groups}>
              {groupsLink}
            </div>
            <table className={`${styles.user_info} ${styles.floatL}`}>
              <tbody>
                <tr>
                  <td><div className={styles.info}>役　職:</div></td>
                  <td className={styles.td}>
                    <div className={styles.info_data}>
                      <InlineTextInput
                        value={position}
                        onSubmit={(value, completion) =>
                          dispatchPutUser(userId, { position: value }).then(completion)}
                      />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><div className={styles.info}>電　話:</div></td>
                  <td>
                    <div className={styles.info_data}>
                      <InlineTextInput
                        value={phoneNumber}
                        onSubmit={(value, completion) =>
                          dispatchPutUser(userId, { phoneNumber: value }).then(completion)}
                      />
                    </div>
                  </td>
                </tr>
                <tr>
                  <td><div className={styles.info}>メール:</div></td>
                  <td>
                    <div className={styles.info_data}>
                      <InlineTextInput
                        value={emailAddress}
                        required
                        onSubmit={(value, completion) =>
                          dispatchPutUser(userId, { emailAddress: value }).then(completion)}
                      />
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>);
  }
}
