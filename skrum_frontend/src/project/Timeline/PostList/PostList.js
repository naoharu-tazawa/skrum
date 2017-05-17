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
      <div className={styles.component}>
        <div className={styles.okrBars}>
          {items.map(post =>
            <div key={post.id} className={styles.okrBar}>
              <PostBar timeline={post} />
            </div>)}
        </div>
      </div>);
  }
}
