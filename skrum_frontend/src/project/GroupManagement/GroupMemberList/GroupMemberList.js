import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { isEmpty } from 'lodash';
import { groupMembersPropTypes } from './propTypes';
import GroupMemberBar from './GroupMemberBar';
import DialogForm from '../../../dialogs/DialogForm';
import EntitySubject from '../../../components/EntitySubject';
import { EntityType } from '../../../util/EntityUtil';
import { withModal } from '../../../util/ModalUtil';
import GroupUserSearch from '../../GroupUserSearch/GroupUserSearch';
import styles from './GroupMemberList.css';

class GroupMemberList extends Component {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    groupName: PropTypes.string.isRequired,
    roleLevel: PropTypes.number.isRequired,
    items: groupMembersPropTypes.isRequired,
    dispatchAddGroupMember: PropTypes.func.isRequired,
    dispatchDeleteGroupMember: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
    closeActiveModal: PropTypes.func.isRequired,
  };

  addMemberDialog = ({ group, onClose }) => (
    <DialogForm
      title="グループメンバーの追加"
      message="以下のグループに追加するメンバーを検索し、追加ボタンを押してください。"
      submitButton="追加"
      onSubmit={({ newMember } = {}) =>
        this.props.dispatchAddGroupMember(group.id, newMember.userId).then(({ error }) =>
          !error && this.props.closeActiveModal(),
        )}
      valid={({ newMember }) => !isEmpty(newMember)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={group} />
          <section>
            <label>メンバー検索</label>
            <GroupUserSearch
              groupId={group.id}
              onChange={value => setFieldData({ newMember: value })}
            />
          </section>
        </div>}
    </DialogForm>);

  render() {
    const { groupId, groupName, roleLevel, items,
      dispatchDeleteGroupMember, openModal } = this.props;
    return (
      <section className={styles.member_list}>
        <h1 className={styles.ttl_setion}>メンバー一覧</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <GroupMemberBar header roleLevel={roleLevel} />
          {items.map(member =>
            <GroupMemberBar
              key={member.id}
              {...{ roleLevel, groupId, groupName, member, dispatchDeleteGroupMember }}
            />)}
          <div className={`${styles.footer} ${styles.alignC}`}>
            <button
              className={styles.addMember}
              onClick={() => openModal(this.addMemberDialog,
                { group: { id: groupId, name: groupName, type: EntityType.GROUP } })}
            >
              <span className={styles.circle}>
                <img src="/img/common/icn_plus.png" alt="Add" />
              </span>
              <span>メンバーを追加</span>
            </button>
          </div>
        </div>
      </section>);
  }
}

export default withModal(GroupMemberList);
