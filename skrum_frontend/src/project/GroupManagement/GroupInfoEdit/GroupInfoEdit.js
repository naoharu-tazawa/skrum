import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { isEmpty } from 'lodash';
import { groupPropTypes } from './propTypes';
import InlineTextInput from '../../../editors/InlineTextInput';
import InlineTextArea from '../../../editors/InlineTextArea';
import InlinePotentialLeaders from '../../PotentialLeaders/InlinePotentialLeaders';
import InlineEntityImagePicker from '../../../components/InlineEntityImagePicker';
import GroupPathLinks from '../../../components/GroupPathLinks';
import DialogForm from '../../../dialogs/DialogForm';
import EntitySubject from '../../../components/EntitySubject';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import Editable from '../../../components/Editable';
import PathSearch from '../../PathSearch/PathSearch';
import { withModal } from '../../../util/ModalUtil';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';
import { loadImageSrc } from '../../../util/ImageUtil';
import styles from './GroupInfoEdit.css';

class GroupInfoEdit extends Component {

  static propTypes = {
    group: groupPropTypes.isRequired,
    dispatchPutGroup: PropTypes.func.isRequired,
    dispatchPostGroupImage: PropTypes.func.isRequired,
    dispatchChangeGroupLeader: PropTypes.func.isRequired,
    dispatchAddGroupPath: PropTypes.func.isRequired,
    dispatchDeleteGroupPath: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  addPathDialog = ({ group: { groupId, name }, onClose }) => (
    <DialogForm
      title="グループの所属先の追加"
      message="以下のグループを所属させるグループを検索し、追加ボタンを押してください。"
      submitButton="追加"
      onSubmit={({ path } = {}) =>
        this.props.dispatchAddGroupPath(groupId, path.groupPathId)}
      valid={({ path }) => !isEmpty(path)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={{ id: groupId, name, type: EntityType.GROUP }} />
          <section>
            <label>所属先グループ検索</label>
            <PathSearch
              groupId={groupId}
              onChange={value => setFieldData({ path: value })}
            />
          </section>
        </div>}
    </DialogForm>);

  render() {
    const { group, dispatchPutGroup, dispatchPostGroupImage,
      dispatchChangeGroupLeader, dispatchDeleteGroupPath, openModal } = this.props;
    const { groupId, name, groupPaths, mission, leaderUserId, leaderName, lastUpdate } = group;
    const entity = { id: groupId, type: EntityType.GROUP };
    return (
      <section className={styles.profile_box}>
        <h1 className={styles.ttl_setion}>基本情報</h1>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <div className={styles.profile_img}>
            <Editable
              entity={entity}
              fallback={<EntityLink entity={entity} avatarSize="70px" avatarOnly />}
            >
              <InlineEntityImagePicker
                entity={entity}
                avatarSize="70px"
                onSubmit={file => loadImageSrc(file).then(({ image, mimeType }) =>
                  dispatchPostGroupImage(groupId, image, mimeType))}
              />
            </Editable>
            <p>最終更新: {toRelativeTimeText(lastUpdate)}</p>
          </div>
          <div className={styles.profile_txt}>
            <h2 className={styles.team_name}>
              <InlineTextInput
                value={name}
                required
                onSubmit={value => dispatchPutGroup(groupId, { name: value })}
              />
            </h2>
            {groupPaths.map(path =>
              <GroupPathLinks
                key={path.groupTreeId}
                groupId={groupId}
                groupName={name}
                path={path}
                dispatchDeleteGroupPath={dispatchDeleteGroupPath}
              />)}
            <button
              className={styles.addPath}
              onClick={() => openModal(this.addPathDialog, { group })}
            >
              所属先グループを追加
            </button>
            <div>
              <div className={styles.title}>ミッション</div>
              <div className={styles.txt}>
                <InlineTextArea
                  value={mission}
                  placeholder="ミッションを入力してください"
                  maxLength={250}
                  onSubmit={value => dispatchPutGroup(groupId, { mission: value })}
                />
              </div>
            </div>
            <EntityLink
              componentClassName={styles.leader}
              entity={{ id: leaderUserId, type: EntityType.USER }}
              title="リーダー"
              editor={
                <InlinePotentialLeaders
                  groupId={groupId}
                  leaderUserId={leaderUserId}
                  leaderName={leaderName}
                  placeholder="リーダーが決まっていません"
                  onSubmit={({ userId, name: userName }) =>
                    dispatchChangeGroupLeader(userId, userName)}
                />}
              local
            />
          </div>
        </div>
      </section>
    );
  }
}

export default withModal(GroupInfoEdit);
