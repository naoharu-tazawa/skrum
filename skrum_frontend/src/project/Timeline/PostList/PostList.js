import React, { Component } from 'react';
import _ from 'lodash';
import { postsPropTypes } from './propTypes';
import PostBar from './PostBar';
import styles from './PostList.css';

export default class PostList extends Component {

  static propTypes = {
    items: postsPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;
    const mapReplies = reply => (
      <div
        key={reply.postId}
        tabIndex={reply.postId}
        className={styles.okrBars}
      >
        <PostBar reply={reply} />
      </div>);

    return (
      <div className={styles.component}>
        <div className={styles.okrBars}>
          {_.flatten(items.map((post) => {
            return [
              (<div key={post.id} className={styles.okrBar}>
                <PostBar timeline={post} />
              </div>),
              ...(post.replies.map(mapReplies)),
            ];
          }))}
        </div>
      </div>);
  }
}
