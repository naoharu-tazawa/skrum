import React, { Component } from 'react';
import { postPropTypes, replyPropTypes } from './propTypes';
import styles from './PostBar.css';

export default class PostBar extends Component {

  static propTypes = {
    timeline: postPropTypes,
    reply: replyPropTypes,
  };

  getPost() {
    const { posterId, posterName, post, postedDatetime, likesCount,
    likedFlg } = this.props.timeline || this.props.reply;

    return (
      <div className={styles.timeline_block}>
        <div className={styles.icn_circle} />
        <div className={styles.timeline_content}>
          <p className={styles.time}>{postedDatetime}<span>◯時間前</span></p>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>{posterName}</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <p>{post}</p>
              <div className={styles.comments}>
                <span className={styles.fb_comment}><img src="/img/common/icn_good.png" alt="" width="20" />{likesCount}件{posterId}{likedFlg}</span>
                <span>コメント ◯件</span>
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
  }

  getReply() {
    const { posterId, posterName, post, postedDatetime } = this.props.reply;

    return (
      <div className={styles.timeline_block}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>{posterName}{posterId}</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <p>{post}</p>
              <div className={styles.comments} />
            </div>
            <div className={styles.btn_area} />
          </div>
          <p className={styles.time}>{postedDatetime}<span>◯時間前</span></p>
        </div>
      </div>);
  }

  getReplyArea() {
    return (
      <div className={styles.timeline_block}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>User Name</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <textarea placeholder="コメントをする" />
            </div>
            <div className={styles.btn_area}>
              <div className={`${styles.btn} ${styles.btn_comment}`}><button>投稿する</button></div>
            </div>
          </div>
        </div>
      </div>);
  }

  render() {
    const { posterId, posterName, post, postedDatetime, likesCount,
    likedFlg } = this.props.timeline || this.props.reply;

    const main = (
      <div className={styles.timeline_block}>
        <div className={styles.icn_circle} />
        <div className={styles.timeline_content}>
          <p className={styles.time}>{postedDatetime}<span>◯時間前</span></p>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>{posterName}</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <p>{post}</p>
              <div className={styles.comments}>
                <span className={styles.fb_comment}><img src="/img/common/icn_good.png" alt="" width="20" />{likesCount}件{posterId}{likedFlg}</span>
                <span>コメント ◯件</span>
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

    const reply = (
      <div className={styles.timeline_block_sub}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>{posterName}{posterId}</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <p>{post}</p>
              <div className={styles.comments} />
            </div>
            <div className={styles.btn_area} />
          </div>
          <p className={styles.time}>{postedDatetime}<span>◯時間前</span></p>
        </div>
      </div>);

    const replyArea = (
      <div className={styles.timeline_block_sub}>
        <div className={styles.timeline_content}>
          <div className={styles.content_inner}>
            <div className={styles.user_name}>
              <dl>
                <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                <dd>User Name</dd>
              </dl>
            </div>
            <div className={styles.text}>
              <textarea placeholder="コメントをする" />
            </div>
            <div className={styles.btn_area}>
              <div className={`${styles.btn} ${styles.btn_comment}`}><button>投稿する</button></div>
            </div>
          </div>
        </div>
      </div>);

    if (this.props.reply) {
      return reply;
    }

    console.log(replyArea);

    return main;
  }
}
