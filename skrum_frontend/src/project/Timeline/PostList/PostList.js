import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { last } from 'lodash';
import Waypoint from 'react-waypoint';
import { postsPropTypes } from './propTypes';
import PostBar from './PostBar';
import styles from './PostList.css';

export default class PostList extends Component {

  static propTypes = {
    isFetchingMore: PropTypes.bool.isRequired,
    hasMorePosts: PropTypes.bool.isRequired,
    items: postsPropTypes.isRequired,
    currentUserId: PropTypes.number.isRequired,
    dispatchFetchMorePosts: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeletePost: PropTypes.func.isRequired,
    dispatchPostLike: PropTypes.func.isRequired,
    dispatchDeleteLike: PropTypes.func.isRequired,
    dispatchPostReply: PropTypes.func.isRequired,
  };

  render() {
    const { isFetchingMore, hasMorePosts, items, currentUserId,
      dispatchFetchMorePosts, dispatchChangeDisclosureType, dispatchDeletePost,
      dispatchPostLike, dispatchDeleteLike, dispatchPostReply } = this.props;
    const lastId = (last(items) || {}).id;
    return (
      <section className={styles.timeline_box}>
        {items.map(post =>
          <PostBar
            key={post.id}
            {...{
              post,
              currentUserId,
              dispatchChangeDisclosureType,
              dispatchDeletePost,
              dispatchPostLike,
              dispatchDeleteLike,
              dispatchPostReply,
            }}
          />)}
        {!isFetchingMore && hasMorePosts && (
          <Waypoint onEnter={() => dispatchFetchMorePosts(lastId)}>
            <div className={styles.spinner} />
          </Waypoint>)}
      </section>);
  }
}
