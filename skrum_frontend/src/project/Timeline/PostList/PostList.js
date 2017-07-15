import React, { Component } from 'react';
import { postsPropTypes } from './propTypes';
import PostBar from './PostBar';
import styles from './PostList.css';

export default class PostList extends Component {

  static propTypes = {
    items: postsPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;

    return (
      <section className={styles.timeline_box}>
        {items.map(post => <PostBar key={post.id} timeline={post} />)}
      </section>);
  }
}
