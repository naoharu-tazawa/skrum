import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupMemberPropTypes } from './propTypes';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import Permissible from '../../../components/Permissible';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntitySubject from '../../../components/EntitySubject';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import { isBasicRole } from '../../../util/UserUtil';
import { withModal } from '../../../util/ModalUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';
import styles from './GroupMemberBar.css';

class GroupMemberBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    roleLevel: PropTypes.number.isRequired,
    groupId: PropTypes.number,
    groupName: PropTypes.string,
    member: groupMemberPropTypes,
    dispatchDeleteGroupMember: PropTypes.func,
    openModal: PropTypes.func.isRequired,
  };

  getHeaderBasic = () => (
    <div className={styles.header}>
      <div className={styles.name}>名前</div>
      <div className={styles.position}>役職</div>
      <div className={styles.update}>最終ログイン</div>
      <div className={styles.delete} />
    </div>);

  getHeaderAdmin = () => (
    <div className={styles.header}>
      <div className={styles.nameAdmin}>名前</div>
      <div className={styles.position}>役職</div>
      <div className={styles.progress}>進捗状況</div>
      <div className={styles.updateAdmin}>最終ログイン</div>
      <div className={styles.delete} />
    </div>);

  deleteMemberPrompt = ({ groupId, groupName, id, name, onClose }) => (
    <ConfirmationPrompt
      title="グループメンバーの削除"
      prompt={`${groupName}からこちらのメンバーを削除しますか？`}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchDeleteGroupMember(groupId, id)}
      onClose={onClose}
    >
      <EntitySubject entity={{ id, name, type: EntityType.USER }} />
    </ConfirmationPrompt>);

  render() {
    const { header, groupId, groupName, member, roleLevel, openModal } = this.props;
    const isBasic = isBasicRole(roleLevel);
    if (header) {
      return isBasic ? this.getHeaderBasic() : this.getHeaderAdmin();
    }
    const { id, name, position, achievementRate, lastLogin } = member;
    return (
      <div className={styles.row}>
        <div className={isBasic ? styles.name : styles.nameAdmin}>
          <EntityLink entity={{ id, name, type: EntityType.USER }} route={{ tab: 'objective' }} />
        </div>
        <span className={styles.position}>{position}</span>
        {!isBasic && (
          <ProgressPercentage
            componentClassName={styles.progress}
            achievementRate={achievementRate}
          />)}
        <span className={isBasic ? styles.update : styles.updateAdmin}>
          {lastLogin && toRelativeTimeText(lastLogin)}
        </span>
        <Permissible entity={{ id: groupId, type: EntityType.GROUP }}>
          <button
            className={styles.delete}
            onClick={() => openModal(this.deleteMemberPrompt, { groupId, groupName, id, name })}
          >
            <img src="/img/delete.png" alt="" />
          </button>
        </Permissible>
      </div>);
  }
}

export default withModal(GroupMemberBar);
