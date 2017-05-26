import React, { Component } from 'react';
import { connect } from 'react-redux';
import { postsPropTypes } from './propTypes';
import PostList from './PostList';

class PostListContainer extends Component {

  static propTypes = {
    items: postsPropTypes,
  };

  render() {
    const { items = [] } = this.props;
    return (
      <PostList
        items={items}
      />);
  }
}

const mapReplies = (reply) => {
  const { postId, posterId, posterName, post, postedDatetime } = reply;
  return {
    postId,
    posterId,
    posterName,
    post,
    postedDatetime,
  };
};

const mapStateToProps = (state) => {
  const { data = [] } = state.timeline || {};
  const items = data.map((item) => {
    const { postId, posterId, posterName, post, postedDatetime,
    okrId, likesCount, likedFlg, replies = [] } = item;
    return {
      id: postId,
      posterId,
      posterName,
      post,
      postedDatetime,
      okrId,
      likesCount,
      likedFlg,
      replies: replies.map(mapReplies),
    };
  });
  return { items };
};

export default connect(
  mapStateToProps,
)(PostListContainer);
