import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupPropTypes } from './propTypes';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import EntitySubject from '../../../components/EntitySubject';
import DropdownMenu from '../../../components/DropdownMenu';
import { GroupType, GroupTypeName } from '../../../util/GroupUtil';
import { isBasicRole } from '../../../util/UserUtil';
import { implodePath } from '../../../util/RouteUtil';
import { withModal } from '../../../util/ModalUtil';
import styles from './GroupBar.css';

class GroupBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    group: groupPropTypes,
    currentRoleLevel: PropTypes.number,
    dispatchDeleteGroup: PropTypes.func,
    openModal: PropTypes.func.isRequired,
  };

  deleteGroupPrompt = ({ id, name, onClose }) => (
    <ConfirmationPrompt
      title="グループの削除"
      prompt="こちらのグループを削除しますか？"
      warning={(
        <ul>
          <li>削除したグループは復元できません。</li>
        </ul>
      )}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchDeleteGroup(id)}
      onClose={onClose}
    >
      <EntitySubject entity={{ id, name, type: EntityType.GROUP }} />
    </ConfirmationPrompt>);

  render() {
    const { header, group, currentRoleLevel, openModal } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.name}>名前</div>
          <div className={styles.type}>グループ種別</div>
          <div className={styles.action} />
        </div>);
    }
    const { id, type, name } = group;
    const allowManaging = !isBasicRole(currentRoleLevel) || type !== GroupType.DEPARTMENT;
    return (
      <div className={styles.bar}>
        <div className={styles.name}>
          <EntityLink entity={{ id, name, type: EntityType.GROUP }} local />
        </div>
        <div className={styles.type}>{GroupTypeName[type]}</div>
        <div className={styles.action}>
          <DropdownMenu
            options={[
              { caption: '目標を見る',
                path: implodePath({ tab: 'objective', subject: 'group', id }) },
              { caption: '所属メンバーを見る',
                path: implodePath({ tab: 'group', subject: 'group', id }) },
              ...allowManaging && [{ caption: 'グループ削除',
                onClick: () => openModal(this.deleteGroupPrompt,
                  { id, name }) }],
            ]}
            align="right"
          />
        </div>
      </div>);
  }
}

export default withModal(GroupBar);
