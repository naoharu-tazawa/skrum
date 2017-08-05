import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import DeletionPrompt from '../dialogs/DeletionPrompt';
import { withModal } from '../util/ModalUtil';
import { replacePath } from '../util/RouteUtil';
import styles from './GroupPathLinks.css';

export const groupPathPropTypes = PropTypes.shape({
  groupTreeId: PropTypes.number.isRequired,
  groupPath: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
  })),
});

class GroupPathLinks extends Component {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    groupName: PropTypes.string.isRequired,
    path: groupPathPropTypes.isRequired,
    local: PropTypes.bool,
    readonly: PropTypes.bool,
    dispatchDeleteGroupPath: PropTypes.func,
    openModal: PropTypes.func,
  };

  deletePathPrompt = ({ groupId, groupName, groupTreeId, path, onClose }) => (
    <DeletionPrompt
      title="グループの所属先の削除"
      prompt={`${groupName}の以下の所属先を削除しますか？`}
      onDelete={() => this.props.dispatchDeleteGroupPath(groupId, groupTreeId)}
      onClose={onClose}
    >
      <div className={styles.box}>
        <GroupPathLinks groupId={groupId} groupName={groupName} path={path} local readonly />
      </div>
    </DeletionPrompt>);

  render() {
    const { groupId, groupName, path, local, readonly, openModal } = this.props;
    const { groupTreeId, groupPath } = path;
    return (
      <ul className={`${styles.component} ${local && styles.local}`}>
        {groupPath.map(({ id, name }, index) =>
          <li key={id} className={styles.path}>
            {local ? name : (
              <Link
                to={replacePath({ subject: !index ? 'company' : 'group', id })}
                className={styles.groupLink}
              >
                {name}
              </Link>)}
            {!readonly && index === (groupPath.length - 1) && (
              <button
                className={styles.delete}
                onClick={() =>
                  openModal(this.deletePathPrompt, { groupId, groupName, groupTreeId, path })}
              >
                <img src="/img/delete.svg" alt="" />
              </button>)}
          </li>)}
      </ul>);
  }
}

export default withModal(GroupPathLinks);
