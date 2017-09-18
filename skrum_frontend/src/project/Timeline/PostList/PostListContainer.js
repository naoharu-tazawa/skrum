import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { postsPropTypes } from './propTypes';
import PostList from './PostList';
import { deletePost, changeDisclosureType, postLike, deleteLike, postReply } from '../action';
import { EntityType } from '../../../util/EntityUtil';
import { mapPoster } from '../../../util/PosterUtil';
import { mapOwner } from '../../../util/OwnerUtil';

class PostListContainer extends Component {

  static propTypes = {
    isFetchingMore: PropTypes.bool,
    hasMorePosts: PropTypes.bool,
    items: postsPropTypes,
    currentUserId: PropTypes.number.isRequired,
    dispatchFetchMorePosts: PropTypes.func,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeletePost: PropTypes.func.isRequired,
    dispatchPostLike: PropTypes.func.isRequired,
    dispatchDeleteLike: PropTypes.func.isRequired,
    dispatchPostReply: PropTypes.func.isRequired,
  };

  render() {
    const { isFetchingMore, hasMorePosts, items = [], currentUserId,
      dispatchFetchMorePosts, dispatchChangeDisclosureType, dispatchDeletePost,
      dispatchPostLike, dispatchDeleteLike, dispatchPostReply } = this.props;
    return (
      <PostList
        {...{
          isFetchingMore,
          hasMorePosts,
          items,
          currentUserId,
          dispatchFetchMorePosts,
          dispatchChangeDisclosureType,
          dispatchDeletePost,
          dispatchPostLike,
          dispatchDeleteLike,
          dispatchPostReply,
        }}
      />);
  }
}

const mapReplies = (reply) => {
  const { postId, posterUserId, posterUserName, post, postedDatetime } = reply;
  return {
    id: postId,
    post,
    poster: { id: posterUserId, name: posterUserName, type: EntityType.USER },
    postedDatetime,
  };
};

const mapStateToProps = (state) => {
  const { userId: currentUserId } = state.auth || {};
  const { posts = [] } = state.timeline || {};
  const items = posts.map((item) => {
    const { postId, post, postedDatetime, disclosureType, autoShare,
      likesCount, likedFlg, replies = [], ...itemOthers } = item;
    const { autoPost, okrId, okrName, ...autoShareOthers } = autoShare || {};
    return {
      id: postId,
      post,
      poster: mapPoster(itemOthers),
      postedDatetime,
      disclosureType,
      autoShare: autoShare && { autoPost, okrId, okrName, owner: mapOwner(autoShareOthers) },
      likesCount,
      likedFlg,
      replies: replies.map(mapReplies),
    };
  });
  return { items, currentUserId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchChangeDisclosureType = (postId, disclosureType) =>
    dispatch(changeDisclosureType(postId, disclosureType));
  const dispatchDeletePost = postId =>
    dispatch(deletePost(postId));
  const dispatchPostLike = postId =>
    dispatch(postLike(postId));
  const dispatchDeleteLike = postId =>
    dispatch(deleteLike(postId));
  const dispatchPostReply = (postId, post) =>
    dispatch(postReply(postId, post));
  return {
    dispatchChangeDisclosureType,
    dispatchDeletePost,
    dispatchPostLike,
    dispatchDeleteLike,
    dispatchPostReply,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PostListContainer);
