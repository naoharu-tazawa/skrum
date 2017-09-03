import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { userGroupPropTypes } from './propTypes';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntitySubject from '../../../components/EntitySubject';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import { withModal } from '../../../util/ModalUtil';
import styles from './UserGroupBar.css';

class UserGroupBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    userId: PropTypes.number,
    userName: PropTypes.string,
    group: userGroupPropTypes,
    dispatchLeaveGroup: PropTypes.func,
    openModal: PropTypes.func.isRequired,
  };

  leaveGroupPrompt = ({ userId, userName, id, name, onClose }) => (
    <ConfirmationPrompt
      title="所属グループからの退出"
      prompt={`${userName} さんをこちらのグループから退出させますか？`}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchLeaveGroup(userId, id)}
      onClose={onClose}
    >
      <EntitySubject entity={{ id, name, type: EntityType.USER }} />
    </ConfirmationPrompt>);

  render() {
    const { header, userId, userName, group, openModal } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.name}>名前</div>
          <div className={styles.progress}>進捗状況</div>
          <div className={styles.delete} />
        </div>);
    }
    const { id, name, achievementRate } = group;
    return (
      <div className={styles.row}>
        <div className={styles.name}>
          <EntityLink entity={{ id, name, type: EntityType.GROUP }} />
        </div>
        <ProgressPercentage
          componentClassName={styles.progress}
          achievementRate={achievementRate}
        />
        <button
          className={styles.delete}
          onClick={() => openModal(this.leaveGroupPrompt, { userId, userName, id, name })}
        >
          <img src="/img/delete.png" alt="" />
        </button>
      </div>);
  }
}

export default withModal(UserGroupBar);
