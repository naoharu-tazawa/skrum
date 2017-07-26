import React, { Component } from 'react';
import { postPropTypes } from './propTypes';
import { EntityType } from '../../../util/EntityUtil';
import styles from './PostBar.css';

export default class PostBar extends Component {

  static propTypes = {
    timeline: postPropTypes,
  };

  render() {
    const { posterType, posterUserId, posterUserName, posterGroupId, posterGroupName,
      posterCompanyId, posterCompanyName, post, postedDatetime, likesCount,
      likedFlg, replies = [] } = this.props.timeline;

    const main = () => (
      <div className={styles.timeline_block}>
        <div className={styles.icn_circle} />
        <div className={styles.timeline_content}>
          <p className={styles.time}>{postedDatetime}<span>◯時間前</span></p>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>
                  {(() => {
                    if (posterType === EntityType.USER) {
                      return posterUserName;
                    } else if (posterType === EntityType.GROUP) {
                      return posterGroupName;
                    }
                    return posterCompanyName;
                  })()}
                </dd>
              </dl>
            </div>
            <div className={styles.text}>
              <p>{post}</p>
              <div className={styles.comments}>
                <span className={styles.fb_comment}><img src="/img/common/icn_good.png" alt="" width="20" />{likesCount}件{posterUserId}{posterGroupId}{posterCompanyId}{likedFlg}</span>
                <span>コメント {replies.length}件</span>
              </div>
            </div>
            <div className={styles.btn_area}>
              <div className={styles.btn}><button><img src="/img/common/icn_good.png" alt="" width="36" /></button></div>
              <div className={styles.btn}><button><img src="/img/common/icn_balloon.png" alt="" width="36" /></button></div>
              <div className={styles.btn}><button><img src="/img/common/icn_more.png" alt="" width="36" /></button></div>
            </div>
          </div>
        </div>
      </div>);

    const reply = item => (
      <div key={item.postId} className={styles.timeline_block_sub}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>{item.posterUserName}</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <p>{item.post}</p>
              <div className={styles.comments} />
            </div>
            <div className={styles.btn_area} />
          </div>
          <p className={styles.time}>{item.postedDatetime}<span>◯時間前</span></p>
        </div>
      </div>);

    const replyArea = () => (
      <div className={`${styles.timeline_block_sub} ${styles.timeline_block_last}`}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={`${styles.user_image} ${styles.floatR}`}>
              <img src="/img/common/icn_user.png" alt="" />
            </div>
            <div className={styles.text}>
              <textarea placeholder="コメントする" />
            </div>
            <div className={styles.btn_area}>
              <div className={`${styles.btn} ${styles.btn_comment}`}><button>投稿する</button></div>
            </div>
          </div>
        </div>
      </div>);

    return (
      <div>
        {main()}
        {replies.reverse().map(reply)}
        {replyArea()}
      </div>);
  }
}
