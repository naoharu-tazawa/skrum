import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { toastr } from 'react-redux-toastr';
import { userPropTypes } from './propTypes';
import UserRolesDropdown from '../UserRolesDropdown';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import DialogForm from '../../../dialogs/DialogForm';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import EntitySubject from '../../../components/EntitySubject';
import DropdownMenu from '../../../components/DropdownMenu';
import { RoleLevelName, isAuthoritativeOver } from '../../../util/UserUtil';
import { formatDate, toRelativeTimeText } from '../../../util/DatetimeUtil';
import { implodePath } from '../../../util/RouteUtil';
import { withModal } from '../../../util/ModalUtil';
import styles from './UserBar.css';

class UserBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    user: userPropTypes,
    currentUserId: PropTypes.number,
    currentRoleLevel: PropTypes.number,
    dispatchResetPassword: PropTypes.func,
    dispatchAssignRole: PropTypes.func,
    dispatchDeleteUser: PropTypes.func,
    openModal: PropTypes.func.isRequired,
  };

  resetPasswordPrompt = ({ id, name, onClose }) => (
    <ConfirmationPrompt
      title="パスワードのリセット"
      prompt="こちらのユーザのパスワードをリセットしますか？"
      confirmButton="リセット"
      onConfirm={() => this.props.dispatchResetPassword(id)
        .then(({ error /* , payload: { message } = {} */ } = {}) => {
          if (error) { toastr.error('パスワードリセットに失敗しました'); }
          if (!error) { toastr.info('仮パスワードを対象ユーザにメール送信しました'); }
        })}
      onClose={onClose}
    >
      <EntitySubject entity={{ id, name, type: EntityType.USER }} />
    </ConfirmationPrompt>);

  changeRoleLevelDialog = ({ id, name, roleAssignmentId: currentAssignmentId, onClose }) => (
    <DialogForm
      title="ユーザ権限の変更"
      submitButton="変更"
      onSubmit={({ roleAssignmentId }) => this.props.dispatchAssignRole(id, roleAssignmentId)}
      valid={({ roleAssignmentId }) => roleAssignmentId && roleAssignmentId !== currentAssignmentId}
      onClose={onClose}
    >
      {({ setFieldData, data: { roleAssignmentId } }) =>
        <div>
          <EntitySubject entity={{ id, name, type: EntityType.USER }} />
          <section>
            <label>対象ユーザのユーザ権限を指定してください</label>
            <UserRolesDropdown
              value={roleAssignmentId || currentAssignmentId}
              onChange={({ value }) => setFieldData({ roleAssignmentId: value })}
            />
          </section>
        </div>}
    </DialogForm>);

  deleteUserPrompt = ({ id, name, onClose }) => (
    <ConfirmationPrompt
      title="ユーザの削除"
      prompt="こちらのユーザを削除しますか？"
      warning={(
        <ul>
          <li>削除したユーザは復元できません。</li>
        </ul>
      )}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchDeleteUser(id)}
      onClose={onClose}
    >
      <EntitySubject entity={{ id, name, type: EntityType.USER }} />
    </ConfirmationPrompt>);

  render() {
    const { header, user, currentUserId, currentRoleLevel, openModal } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.name}>名前</div>
          <div className={styles.role}>権限</div>
          <div className={styles.lastLogin}>最終ログイン</div>
          <div className={styles.action} />
        </div>);
    }
    const { id, name, roleAssignmentId, roleLevel, lastLogin } = user;
    const allowManaging = currentUserId !== id && isAuthoritativeOver(currentRoleLevel, roleLevel);
    return (
      <div className={styles.bar}>
        <div className={styles.name}>
          <EntityLink entity={{ id, name, type: EntityType.USER }} local />
        </div>
        <div className={styles.role}>{RoleLevelName[roleLevel]}</div>
        <div className={styles.lastLogin}>
          {lastLogin && `${formatDate(lastLogin)}（${toRelativeTimeText(lastLogin)}）`}
        </div>
        <div className={styles.action}>
          <DropdownMenu
            options={[
              { caption: '目標を見る',
                path: implodePath({ tab: 'objective', subject: 'user', id }) },
              { caption: '所属グループを見る',
                path: implodePath({ tab: 'control', subject: 'user', id }) },
              ...allowManaging && [{ caption: 'パスワードリセット',
                onClick: () => openModal(this.resetPasswordPrompt,
                  { id, name }) }],
              ...allowManaging && [{ caption: 'ユーザ権限変更',
                onClick: () => openModal(this.changeRoleLevelDialog,
                  { id, name, roleAssignmentId }) }],
              ...allowManaging && [{ caption: 'ユーザ削除',
                onClick: () => openModal(this.deleteUserPrompt,
                  { id, name }) }],
            ]}
            align="right"
          />
        </div>
      </div>);
  }
}

export default withModal(UserBar);
