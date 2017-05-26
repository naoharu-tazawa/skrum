import React, { Component } from 'react';
import { postPropTypes, replyPropTypes } from './propTypes';
import styles from './PostBar.css';

const imageDim = 2.25;
const progressBarWidth = 10;
const ownerBoxWidth = 10;

const colStyle = {
  mapImage: {
    minWidth: `${imageDim}em`,
//    height: `${imageDim}em`,
  },
  name: {
    width: '100%',
  },
  progressBar: {
    minWidth: `${progressBarWidth}em`,
  },
  ownerBox: {
    minWidth: `${ownerBoxWidth}em`,
  },
  tool: {
    minWidth: `${imageDim}em`,
    height: `${imageDim}em`,
  },
};

export default class PostBar extends Component {

  static propTypes = {
    timeline: postPropTypes,
    reply: replyPropTypes,
  };

  render() {
    const { posterId, posterName, post, postedDatetime, likesCount,
    likedFlg } = this.props.timeline || this.props.reply;
    return (
      <div className={styles.component}>
        <div className={styles.name} style={colStyle.name}>{posterId}</div>
        <div className={styles.name} style={colStyle.name}>{posterName}</div>
        <div className={styles.name} style={colStyle.name}>{postedDatetime}</div>
        <div className={styles.name} style={colStyle.name}>{post}</div>
        <div className={styles.name} style={colStyle.name}>{likesCount}</div>
        <div className={styles.name} style={colStyle.name}>{likedFlg}</div>
      </div>);
  }
}
