import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { postsPropTypes } from './propTypes';
import PostList from './PostList';
import { deleteGroupPosts, changeDisclosureType, postLike, deleteLike, postReply } from '../action';
import { EntityType } from '../../../util/EntityUtil';
import { mapPoster } from '../../../util/PosterUtil';
import { mapOwner } from '../../../util/OwnerUtil';

class PostListContainer extends Component {

  static propTypes = {
    isFetchingMore: PropTypes.bool,
    hasMorePosts: PropTypes.bool,
    items: postsPropTypes,
    roleLevel: PropTypes.number.isRequired,
    currentUserId: PropTypes.number.isRequired,
    dispatchFetchMoreGroupPosts: PropTypes.func,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteGroupPosts: PropTypes.func.isRequired,
    dispatchPostLike: PropTypes.func.isRequired,
    dispatchDeleteLike: PropTypes.func.isRequired,
    dispatchPostReply: PropTypes.func.isRequired,
  };

  render() {
    const { isFetchingMore, hasMorePosts, items = [], roleLevel, currentUserId,
      dispatchFetchMoreGroupPosts, dispatchChangeDisclosureType, dispatchDeleteGroupPosts,
      dispatchPostLike, dispatchDeleteLike, dispatchPostReply } = this.props;
    return (
      <PostList
        {...{
          isFetchingMore,
          hasMorePosts,
          items,
          roleLevel,
          currentUserId,
          dispatchFetchMoreGroupPosts,
          dispatchChangeDisclosureType,
          dispatchDeleteGroupPosts,
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
  const { userId: currentUserId, roleLevel } = state.auth || {};
  const { posts = [] } = state.timeline || {};
  const items = posts.map((item) => {
    const { postId, post, postedDatetime, disclosureType, autoShare,
      likesCount, likedFlg, replies = [] } = item;
    const { autoPost, okrId, okrName } = autoShare || {};
    return {
      id: postId,
      post,
      poster: mapPoster(item),
      postedDatetime,
      disclosureType,
      autoShare: autoShare && { autoPost, okrId, okrName, owner: mapOwner(autoShare) },
      likesCount,
      likedFlg,
      replies: replies.map(mapReplies),
    };
  });
  return { items, currentUserId, roleLevel };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchChangeDisclosureType = (postId, disclosureType) =>
    dispatch(changeDisclosureType(postId, disclosureType));
  const dispatchDeleteGroupPosts = postId =>
    dispatch(deleteGroupPosts(postId));
  const dispatchPostLike = postId =>
    dispatch(postLike(postId));
  const dispatchDeleteLike = postId =>
    dispatch(deleteLike(postId));
  const dispatchPostReply = (postId, post) =>
    dispatch(postReply(postId, post));
  return {
    dispatchChangeDisclosureType,
    dispatchDeleteGroupPosts,
    dispatchPostLike,
    dispatchDeleteLike,
    dispatchPostReply,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PostListContainer);
