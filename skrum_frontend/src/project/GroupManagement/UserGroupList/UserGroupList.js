import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { isEmpty } from 'lodash';
import { userGroupsPropTypes } from './propTypes';
import UserGroupBar from './UserGroupBar';
import DialogForm from '../../../dialogs/DialogForm';
import Permissible from '../../../components/Permissible';
import EntitySubject from '../../../components/EntitySubject';
import { EntityType } from '../../../util/EntityUtil';
import { withModal } from '../../../util/ModalUtil';
import UserGroupSearch from '../../UserGroupSearch/UserGroupSearch';
import styles from './UserGroupList.css';

class UserGroupList extends Component {

  static propTypes = {
    userId: PropTypes.number.isRequired,
    userName: PropTypes.string.isRequired,
    roleLevel: PropTypes.number.isRequired,
    groups: userGroupsPropTypes.isRequired,
    dispatchJoinGroup: PropTypes.func.isRequired,
    dispatchLeaveGroup: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  joinGroupDialog = ({ user, onClose }) => (
    <DialogForm
      title="所属グループの追加"
      message="以下のユーザが新しく所属するグループを検索し、追加ボタンを押してください。"
      submitButton="追加"
      onSubmit={({ newGroup } = {}) => this.props.dispatchJoinGroup(user.id, newGroup.groupId)}
      valid={({ newGroup }) => !isEmpty(newGroup)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={user} />
          <section>
            <label>グループ検索</label>
            <UserGroupSearch
              userId={user.id}
              onChange={value => setFieldData({ newGroup: value })}
            />
          </section>
        </div>}
    </DialogForm>);

  render() {
    const { userId, userName, roleLevel, groups, dispatchLeaveGroup, openModal } = this.props;
    const entity = { id: userId, type: EntityType.USER, roleLevel };
    return (
      <section className={styles.group_list}>
        <h1 className={styles.ttl_setion}>所属グループ一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <UserGroupBar header />
          {groups.map(group =>
            <UserGroupBar
              key={group.id}
              {...{ userId, userName, roleLevel, group, dispatchLeaveGroup }}
            />)}
          <Permissible entity={entity}>
            <div className={`${styles.footer} ${styles.alignC}`}>
              <button
                className={styles.addGroup}
                onClick={() => openModal(this.joinGroupDialog,
                  { user: { id: userId, name: userName, type: EntityType.USER } })}
              >
                <span className={styles.circle}>
                  <img src="/img/common/icn_plus.png" alt="Add" />
                </span>
                <span>所属グループを追加</span>
              </button>
            </div>
          </Permissible>
        </div>
      </section>);
  }
}

export default withModal(UserGroupList, { wrapperClassName: styles.wrapper });
