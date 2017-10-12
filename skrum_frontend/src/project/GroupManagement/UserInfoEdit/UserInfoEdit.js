import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { userPropTypes } from './propTypes';
import Permissible from '../../../components/Permissible';
import InlineTextInput from '../../../editors/InlineTextInput';
import InlineEntityImagePicker from '../../../components/InlineEntityImagePicker';
import { EntityType } from '../../../util/EntityUtil';
import { replacePath } from '../../../util/RouteUtil';
import styles from './UserInfoEdit.css';

export default class UserInfoEdit extends Component {

  static propTypes = {
    user: userPropTypes.isRequired,
    dispatchPutUser: PropTypes.func.isRequired,
    dispatchPostUserImage: PropTypes.func.isRequired,
  };

  render() {
    const { user, dispatchPutUser, dispatchPostUserImage } = this.props;
    const { userId, lastName, firstName, roleLevel, departments,
      position, phoneNumber, emailAddress } = user;
    const groupsLink = departments.map(({ groupId, groupName }, index) =>
      <span key={groupId}>
        {index ? '・' : ''}
        <Link
          to={replacePath({ tab: 'objective', subject: 'group', id: groupId })}
          className={styles.groupLink}
        >
          {groupName}
        </Link>
      </span>);
    const entity = { id: userId, type: EntityType.USER, roleLevel };
    return (
      <section className={styles.profile_box}>
        <h1 className={styles.ttl_setion}>基本情報</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <div className={styles.profile_img}>
            <Permissible entity={entity}>
              {({ permitted }) => (
                <InlineEntityImagePicker
                  entity={entity}
                  avatarSize={70}
                  readonly={!permitted}
                  onSubmit={({ image, mimeType }) => dispatchPostUserImage(userId, image, mimeType)}
                />)}
            </Permissible>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.user_name}>
              <span className={styles.user_name_l}>
                <Permissible entity={entity}>
                  {({ permitted }) => (
                    <InlineTextInput
                      value={lastName}
                      required
                      readonly={!permitted}
                      onSubmit={value => dispatchPutUser(userId, { lastName: value })}
                    />)}
                </Permissible>
              </span>
              <span className={styles.user_name_f}>
                <Permissible entity={entity}>
                  {({ permitted }) => (
                    <InlineTextInput
                      value={firstName}
                      required
                      readonly={!permitted}
                      onSubmit={value => dispatchPutUser(userId, { firstName: value })}
                    />)}
                </Permissible>
              </span>
            </h2>
            <div className={styles.groups}>
              {groupsLink}
            </div>
            <div className={`${styles.user_info} ${styles.floatL}`}>
              <div className={styles.info}>
                <small>役　職:</small>
                <div className={styles.info_data}>
                  <Permissible entity={entity}>
                    {({ permitted }) => (
                      <InlineTextInput
                        value={position}
                        maxLength={120}
                        required
                        readonly={!permitted}
                        onSubmit={value => dispatchPutUser(userId, { position: value })}
                      />)}
                  </Permissible>
                </div>
              </div>
              <div className={styles.info}>
                <small>電　話:</small>
                <div className={styles.info_data}>
                  <Permissible entity={entity}>
                    {({ permitted }) => (
                      <InlineTextInput
                        value={phoneNumber}
                        maxLength={45}
                        placeholder="電話番号を入力してください"
                        readonly={!permitted}
                        onSubmit={value => dispatchPutUser(userId, { phoneNumber: value })}
                      />)}
                  </Permissible>
                </div>
              </div>
              <div className={styles.info}>
                <small>メール:</small>
                <div className={styles.info_data}>
                  <Permissible entity={entity}>
                    {({ permitted }) => (
                      <InlineTextInput
                        value={emailAddress}
                        required
                        readonly={!permitted}
                        onSubmit={value => dispatchPutUser(userId, { emailAddress: value })}
                      />)}
                  </Permissible>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>);
  }
}
