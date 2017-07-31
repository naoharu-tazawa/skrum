import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson, deleteJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_GROUP_POSTS: 'REQUEST_FETCH_GROUP_POSTS',
  FINISH_FETCH_GROUP_POSTS: 'FINISH_FETCH_GROUP_POSTS',
  REQUEST_MORE_GROUP_POSTS: 'REQUEST_MORE_GROUP_POSTS',
  FINISH_MORE_GROUP_POSTS: 'FINISH_MORE_GROUP_POSTS',
  REQUEST_POST_GROUP_POSTS: 'REQUEST_POST_GROUP_POSTS',
  FINISH_POST_GROUP_POSTS: 'FINISH_POST_GROUP_POSTS',
  REQUEST_CHANGE_POST_DISCLOSURE_TYPE: 'REQUEST_CHANGE_POST_DISCLOSURE_TYPE',
  FINISH_CHANGE_POST_DISCLOSURE_TYPE: 'FINISH_CHANGE_POST_DISCLOSURE_TYPE',
  REQUEST_DELETE_GROUP_POSTS: 'REQUEST_DELETE_GROUP_POSTS',
  FINISH_DELETE_GROUP_POSTS: 'FINISH_DELETE_GROUP_POSTS',
  REQUEST_POST_LIKE: 'REQUEST_POST_LIKE',
  FINISH_POST_LIKE: 'FINISH_POST_LIKE',
  REQUEST_DELETE_LIKE: 'REQUEST_DELETE_LIKE',
  FINISH_DELETE_LIKE: 'FINISH_DELETE_LIKE',
  REQUEST_POST_GROUP_REPLY: 'REQUEST_POST_GROUP_REPLY',
  FINISH_POST_GROUP_REPLY: 'FINISH_POST_GROUP_REPLY',
};

const {
  requestFetchGroupPosts,
  finishFetchGroupPosts,
  requestMoreGroupPosts,
  finishMoreGroupPosts,
  requestPostGroupPosts,
  finishPostGroupPosts,
  requestChangePostDisclosureType,
  finishChangePostDisclosureType,
  requestDeleteGroupPosts,
  finishDeleteGroupPosts,
  requestPostLike,
  finishPostLike,
  requestDeleteLike,
  finishDeleteLike,
  requestPostGroupReply,
  finishPostGroupReply,
} = createActions({
  [Action.FINISH_FETCH_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_MORE_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_POST_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_CHANGE_POST_DISCLOSURE_TYPE]: keyValueIdentity,
  [Action.FINISH_DELETE_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_POST_LIKE]: keyValueIdentity,
  [Action.FINISH_DELETE_LIKE]: keyValueIdentity,
  [Action.FINISH_POST_GROUP_REPLY]: keyValueIdentity,
},
  Action.REQUEST_FETCH_GROUP_POSTS,
  Action.REQUEST_MORE_GROUP_POSTS,
  Action.REQUEST_POST_GROUP_POSTS,
  Action.REQUEST_CHANGE_POST_DISCLOSURE_TYPE,
  Action.REQUEST_DELETE_GROUP_POSTS,
  Action.REQUEST_POST_LIKE,
  Action.REQUEST_DELETE_LIKE,
  Action.REQUEST_POST_GROUP_REPLY,
);

export const fetchGroupPosts = groupId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetching) return Promise.resolve();
    dispatch(requestFetchGroupPosts());
    return getJson(`/groups/${groupId}/posts.json`, state)()
      .then(json => dispatch(finishFetchGroupPosts('data', json)))
      .catch(({ message }) => dispatch(finishFetchGroupPosts(new Error(message))));
  };

export const fetchMoreGroupPosts = (groupId, before) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetchingMore) return Promise.resolve();
    dispatch(requestMoreGroupPosts());
    return getJson(`/groups/${groupId}/posts.json`, state)({ before })
      .then(json => dispatch(finishMoreGroupPosts('data', json)))
      .catch(({ message }) => dispatch(finishMoreGroupPosts(new Error(message))));
  };

export const postGroupPosts = (groupId, post, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isPosting) return Promise.resolve();
    dispatch(requestPostGroupPosts());
    return postJson(`/groups/${groupId}/posts.json`, state)(null, { post, disclosureType })
      .then(json => dispatch(finishPostGroupPosts('data', json)))
      .catch(({ message }) => dispatch(finishPostGroupPosts(new Error(message))));
  };

export const changeDisclosureType = (postId, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isChangingDisclosureType) return Promise.resolve();
    dispatch(requestChangePostDisclosureType());
    return putJson(`/posts/${postId}/changedisclosure.json`, state)(null, { disclosureType })
      .then(() => dispatch(finishChangePostDisclosureType('data', { postId, disclosureType })))
      .catch(({ message }) => dispatch(finishChangePostDisclosureType(new Error(message))));
  };

export const deleteGroupPosts = postId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isDeleting) return Promise.resolve();
    dispatch(requestDeleteGroupPosts());
    return deleteJson(`/posts/${postId}.json`, state)()
      .then(() => dispatch(finishDeleteGroupPosts('data', { postId })))
      .catch(({ message }) => dispatch(finishDeleteGroupPosts(new Error(message))));
  };

export const postLike = postId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isPostingLike) return Promise.resolve();
    dispatch(requestPostLike());
    return postJson(`/posts/${postId}/likes.json`, state)()
      .then(() => dispatch(finishPostLike('data', { postId })))
      .catch(({ message }) => dispatch(finishPostLike(new Error(message))));
  };

export const deleteLike = postId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isDeletingLike) return Promise.resolve();
    dispatch(requestDeleteLike());
    return deleteJson(`/posts/${postId}/like.json`, state)()
      .then(() => dispatch(finishDeleteLike('data', { postId })))
      .catch(({ message }) => dispatch(finishDeleteLike(new Error(message))));
  };

export const postReply = (postId, post) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isPostingReply) return Promise.resolve();
    dispatch(requestPostGroupReply());
    return postJson(`/posts/${postId}/replies.json`, state)(null, { post })
      .then(json => dispatch(finishPostGroupReply('data', { postId, reply: json })))
      .catch(({ message }) => dispatch(finishPostGroupReply(new Error(message))));
  };
