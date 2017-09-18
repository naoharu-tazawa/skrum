import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Linkify from 'react-linkify';
import { postPropTypes } from './propTypes';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import DialogForm from '../../../dialogs/DialogForm';
import Permissible from '../../../components/Permissible';
import EntitySubject from '../../../components/EntitySubject';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import DisclosureTypeOptions from '../../../components/DisclosureTypeOptions';
import DropdownMenu from '../../../components/DropdownMenu';
import { formatDate, DateFormat, toRelativeTimeText } from '../../../util/DatetimeUtil';
import { withModal } from '../../../util/ModalUtil';
import styles from './PostBar.css';

class PostBar extends Component {

  static propTypes = {
    post: postPropTypes.isRequired,
    currentUserId: PropTypes.number.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeletePost: PropTypes.func.isRequired,
    dispatchPostLike: PropTypes.func.isRequired,
    dispatchDeleteLike: PropTypes.func.isRequired,
    dispatchPostReply: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  changeDisclosureTypeDialog = ({ id, post, poster, disclosureType, onClose }) => (
    <DialogForm
      title="公開範囲設定変更"
      submitButton="設定"
      onSubmit={({ changedDisclosureType } = {}) =>
        (!changedDisclosureType || changedDisclosureType === disclosureType ? Promise.resolve() :
          this.props.dispatchChangeDisclosureType(id, changedDisclosureType))}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={poster} heading="対象投稿" subject={post} />
          <section>
            <label>公開範囲</label>
            <DisclosureTypeOptions
              entityType={poster.type}
              renderer={({ value, label }) => (
                <label key={value}>
                  <input
                    name="disclosureType"
                    type="radio"
                    defaultChecked={value === disclosureType}
                    onClick={() => setFieldData({ changedDisclosureType: value })}
                  />
                  {label}
                </label>)}
            />
          </section>
        </div>}
    </DialogForm>);

  deletePostPrompt = ({ id, post, poster, onClose }) => (
    <ConfirmationPrompt
      title="投稿の削除"
      prompt="こちらの投稿を削除しますか？"
      warning={<ul><li>一度削除した投稿は復元できません。</li></ul>}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchDeletePost(id)}
      onClose={onClose}
    >
      <EntitySubject entity={poster} subject={post} />
    </ConfirmationPrompt>);

  render() {
    const { post: timeline, currentUserId,
      dispatchPostLike, dispatchDeleteLike, dispatchPostReply, openModal } = this.props;
    const { replyPost, isPostingReply } = this.state || {};

    const main = ({ id, post, poster, postedDatetime, disclosureType, autoShare,
      likesCount, likedFlg, replies = [] }) => (
        <div className={styles.timeline_block}>
          <div className={styles.icn_circle} />
          <div className={styles.timeline_content}>
            <div className={styles.content_inner}>
              <div className={styles.poster}>
                <EntityLink entity={poster} route={{ tab: 'objective' }} />
              </div>
              <div className={styles.text}>
                <p className={styles.time}>
                  {formatDate(postedDatetime, { format: DateFormat.YMDHM })}
                  <span>{toRelativeTimeText(postedDatetime)}</span>
                </p>
                {post && <Linkify><p className={styles.post}>{post}</p></Linkify>}
                {autoShare && (
                  <EntitySubject
                    entity={autoShare.owner}
                    heading={autoShare.autoPost}
                    subject={autoShare.okrName}
                    route={{ tab: 'objective' }}
                    aspectRoute={{ aspect: 'o', aspectId: autoShare.okrId }}
                  />
                )}
                <div className={styles.comments}>
                  <span className={styles.fb_comment}>
                    <img src="/img/common/icn_good.png" alt="" width="20px" />
                    {likesCount}件
                  </span>
                  <span>コメント {replies.length}件</span>
                </div>
              </div>
              <div className={styles.btn_area}>
                <div className={styles.btn}>
                  <button
                    className={`${styles.like} ${likedFlg && styles.liked}`}
                    onClick={() => (!likedFlg ? dispatchPostLike(id) : dispatchDeleteLike(id))}
                  />
                </div>
                <Permissible entity={poster}>
                  <DropdownMenu
                    trigger={(
                      <div className={styles.btn}>
                        <button><img src="/img/common/icn_more.png" alt="" width="36px" /></button>
                      </div>)}
                    options={[
                      { caption: '公開範囲設定',
                        onClick: () => openModal(this.changeDisclosureTypeDialog,
                          { id, post, poster, disclosureType }) },
                      { caption: '削除',
                        onClick: () => openModal(this.deletePostPrompt, { id, post, poster }) },
                    ]}
                  />
                </Permissible>
              </div>
            </div>
          </div>
        </div>);

    const reply = ({ id, post, poster, postedDatetime }) => (
      <div key={id} className={styles.timeline_block_sub}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={styles.poster}>
              <EntityLink entity={poster} route={{ tab: 'objective' }} />
            </div>
            <div className={styles.text}>
              <p className={styles.time}>
                {formatDate(postedDatetime, { format: DateFormat.YMDHM })}
                <span>{toRelativeTimeText(postedDatetime)}</span>
              </p>
              <Linkify>{post}</Linkify>
              <div className={styles.comments} />
            </div>
            <div className={styles.btn_area} />
          </div>
        </div>
      </div>);

    const replyArea = ({ id }) => (
      <div className={`${styles.timeline_block_sub} ${styles.timeline_block_last}`}>
        <div className={styles.timeline_content}>
          <div className={styles.reply_area}>
            <div className={styles.poster}>
              <EntityLink entity={{ id: currentUserId, type: EntityType.USER }} local />
            </div>
            <div className={styles.text}>
              <textarea
                placeholder="コメントする"
                onChange={e => this.setState({ replyPost: e.target.value })}
                value={replyPost || ''}
              />
            </div>
            <div className={styles.btn_area}>
              <div className={`${styles.btn} ${styles.btn_comment}`}>
                {isPostingReply ?
                  <div className={styles.busy_btn} /> :
                  <button
                    onClick={() => replyPost && this.setState({ isPostingReply: true },
                      () => dispatchPostReply(id, replyPost).then(({ error }) =>
                        this.setState({ isPostingReply: false }, () =>
                          !error && this.setState({ replyPost: '' }))))}
                  >
                    投稿する
                  </button>}
              </div>
            </div>
          </div>
        </div>
      </div>);

    return (
      <div>
        {main(timeline)}
        {timeline.replies.map(reply)}
        {replyArea(timeline)}
      </div>);
  }
}

export default withModal(PostBar);
