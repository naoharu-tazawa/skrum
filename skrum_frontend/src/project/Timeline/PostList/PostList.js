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
    const mapReplies = reply => (<PostBar key={reply.postId} reply={reply} />);

    return (
      <section className={styles.timeline_box}>
        {_.flatten(items.map((post) => {
          return [
            (<PostBar key={post.id} timeline={post} />),
            ...(post.replies.map(mapReplies)),
          ];
        }))}
      </section>);
  }
}
