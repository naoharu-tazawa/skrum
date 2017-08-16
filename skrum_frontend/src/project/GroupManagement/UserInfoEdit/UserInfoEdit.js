import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { userPropTypes } from './propTypes';
import InlineTextInput from '../../../editors/InlineTextInput';
import InlineEntityImagePicker from '../../../components/InlineEntityImagePicker';
import { EntityType } from '../../../util/EntityUtil';
import { replacePath } from '../../../util/RouteUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';
import { loadImageSrc } from '../../../util/ImageUtil';
import styles from './UserInfoEdit.css';

export default class UserInfoEdit extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
    dispatchPutUser: PropTypes.func.isRequired,
    dispatchPostUserImage: PropTypes.func.isRequired,
  };

  render() {
    const { user, dispatchPutUser, dispatchPostUserImage } = this.props;
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
            <InlineEntityImagePicker
              entity={{ id: userId, type: EntityType.USER }}
              avatarSize="70px"
              onSubmit={file => loadImageSrc(file).then(({ image, mimeType }) =>
                dispatchPostUserImage(userId, image, mimeType))}
            />
            <p>最終更新: {toRelativeTimeText(lastUpdate)}</p>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.user_name}>
              <span className={styles.user_name_l}>
                <InlineTextInput
                  value={lastName}
                  required
                  onSubmit={value => dispatchPutUser(userId, { lastName: value })}
                />
              </span>
              <span className={styles.user_name_f}>
                <InlineTextInput
                  value={firstName}
                  required
                  onSubmit={value => dispatchPutUser(userId, { firstName: value })}
                />
              </span>
            </h2>
            <div className={styles.groups}>
              {groupsLink}
            </div>
            <div className={`${styles.user_info} ${styles.floatL}`}>
              <div className={styles.info}>
                <small>役　職:</small>
                <div className={styles.info_data}>
                  <InlineTextInput
                    value={position}
                    required
                    onSubmit={value => dispatchPutUser(userId, { position: value })}
                  />
                </div>
              </div>
              <div className={styles.info}>
                <small>電　話:</small>
                <div className={styles.info_data}>
                  <InlineTextInput
                    value={phoneNumber}
                    placeholder="電話番号を入力してください"
                    onSubmit={value => dispatchPutUser(userId, { phoneNumber: value })}
                  />
                </div>
              </div>
              <div className={styles.info}>
                <small>メール:</small>
                <div className={styles.info_data}>
                  <InlineTextInput
                    value={emailAddress}
                    required
                    onSubmit={value => dispatchPutUser(userId, { emailAddress: value })}
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>);
  }
}
