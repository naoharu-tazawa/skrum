import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson, deleteJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_POSTS: 'REQUEST_FETCH_USER_POSTS',
  FINISH_FETCH_USER_POSTS: 'FINISH_FETCH_USER_POSTS',
  REQUEST_MORE_USER_POSTS: 'REQUEST_MORE_USER_POSTS',
  FINISH_MORE_USER_POSTS: 'FINISH_MORE_USER_POSTS',
  REQUEST_FETCH_GROUP_POSTS: 'REQUEST_FETCH_GROUP_POSTS',
  FINISH_FETCH_GROUP_POSTS: 'FINISH_FETCH_GROUP_POSTS',
  REQUEST_MORE_GROUP_POSTS: 'REQUEST_MORE_GROUP_POSTS',
  FINISH_MORE_GROUP_POSTS: 'FINISH_MORE_GROUP_POSTS',
  REQUEST_FETCH_COMPANY_POSTS: 'REQUEST_FETCH_COMPANY_POSTS',
  FINISH_FETCH_COMPANY_POSTS: 'FINISH_FETCH_COMPANY_POSTS',
  REQUEST_MORE_COMPANY_POSTS: 'REQUEST_MORE_COMPANY_POSTS',
  FINISH_MORE_COMPANY_POSTS: 'FINISH_MORE_COMPANY_POSTS',
  REQUEST_POST_GROUP_POST: 'REQUEST_POST_GROUP_POST',
  FINISH_POST_GROUP_POST: 'FINISH_POST_GROUP_POST',
  REQUEST_POST_COMPANY_POST: 'REQUEST_POST_COMPANY_POST',
  FINISH_POST_COMPANY_POST: 'FINISH_POST_COMPANY_POST',
  REQUEST_CHANGE_POST_DISCLOSURE_TYPE: 'REQUEST_CHANGE_POST_DISCLOSURE_TYPE',
  FINISH_CHANGE_POST_DISCLOSURE_TYPE: 'FINISH_CHANGE_POST_DISCLOSURE_TYPE',
  REQUEST_DELETE_POST: 'REQUEST_DELETE_POST',
  FINISH_DELETE_POST: 'FINISH_DELETE_POST',
  REQUEST_POST_LIKE: 'REQUEST_POST_LIKE',
  FINISH_POST_LIKE: 'FINISH_POST_LIKE',
  REQUEST_DELETE_LIKE: 'REQUEST_DELETE_LIKE',
  FINISH_DELETE_LIKE: 'FINISH_DELETE_LIKE',
  REQUEST_POST_REPLY: 'REQUEST_POST_REPLY',
  FINISH_POST_REPLY: 'FINISH_POST_REPLY',
};

const {
  requestFetchUserPosts,
  finishFetchUserPosts,
  requestMoreUserPosts,
  finishMoreUserPosts,
  requestFetchGroupPosts,
  finishFetchGroupPosts,
  requestMoreGroupPosts,
  finishMoreGroupPosts,
  requestFetchCompanyPosts,
  finishFetchCompanyPosts,
  requestMoreCompanyPosts,
  finishMoreCompanyPosts,
  requestPostGroupPost,
  finishPostGroupPost,
  requestPostCompanyPost,
  finishPostCompanyPost,
  requestChangePostDisclosureType,
  finishChangePostDisclosureType,
  requestDeletePost,
  finishDeletePost,
  requestPostLike,
  finishPostLike,
  requestDeleteLike,
  finishDeleteLike,
  requestPostReply,
  finishPostReply,
} = createActions({
  [Action.FINISH_FETCH_USER_POSTS]: keyValueIdentity,
  [Action.FINISH_MORE_USER_POSTS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_MORE_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_POSTS]: keyValueIdentity,
  [Action.FINISH_MORE_COMPANY_POSTS]: keyValueIdentity,
  [Action.FINISH_POST_GROUP_POST]: keyValueIdentity,
  [Action.FINISH_POST_COMPANY_POST]: keyValueIdentity,
  [Action.FINISH_CHANGE_POST_DISCLOSURE_TYPE]: keyValueIdentity,
  [Action.FINISH_DELETE_POST]: keyValueIdentity,
  [Action.FINISH_POST_LIKE]: keyValueIdentity,
  [Action.FINISH_DELETE_LIKE]: keyValueIdentity,
  [Action.FINISH_POST_REPLY]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_POSTS,
  Action.REQUEST_MORE_USER_POSTS,
  Action.REQUEST_FETCH_GROUP_POSTS,
  Action.REQUEST_MORE_GROUP_POSTS,
  Action.REQUEST_FETCH_COMPANY_POSTS,
  Action.REQUEST_MORE_COMPANY_POSTS,
  Action.REQUEST_POST_GROUP_POST,
  Action.REQUEST_POST_COMPANY_POST,
  Action.REQUEST_CHANGE_POST_DISCLOSURE_TYPE,
  Action.REQUEST_DELETE_POST,
  Action.REQUEST_POST_LIKE,
  Action.REQUEST_DELETE_LIKE,
  Action.REQUEST_POST_REPLY,
);

export const fetchUserPosts = userId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetching) return Promise.resolve();
    dispatch(requestFetchUserPosts());
    return getJson(`/users/${userId}/posts.json`, state)()
      .then(json => dispatch(finishFetchUserPosts('data', json)))
      .catch(({ message }) => dispatch(finishFetchUserPosts(new Error(message))));
  };

export const fetchMoreUserPosts = (userId, before) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetchingMore) return Promise.resolve();
    dispatch(requestMoreUserPosts());
    return getJson(`/users/${userId}/posts.json`, state)({ before })
      .then(json => dispatch(finishMoreUserPosts('data', json)))
      .catch(({ message }) => dispatch(finishMoreUserPosts(new Error(message))));
  };

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

export const fetchCompanyPosts = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetching) return Promise.resolve();
    dispatch(requestFetchCompanyPosts());
    return getJson(`/companies/${companyId}/posts.json`, state)()
      .then(json => dispatch(finishFetchCompanyPosts('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompanyPosts(new Error(message))));
  };

export const fetchMoreCompanyPosts = (companyId, before) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetchingMore) return Promise.resolve();
    dispatch(requestMoreCompanyPosts());
    return getJson(`/companies/${companyId}/posts.json`, state)({ before })
      .then(json => dispatch(finishMoreCompanyPosts('data', json)))
      .catch(({ message }) => dispatch(finishMoreCompanyPosts(new Error(message))));
  };

export const postGroupPost = (groupId, post, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isPosting) return Promise.resolve();
    dispatch(requestPostGroupPost());
    return postJson(`/groups/${groupId}/posts.json`, state)(null, { post, disclosureType })
      .then(json => dispatch(finishPostGroupPost('data', json)))
      .catch(({ message }) => dispatch(finishPostGroupPost(new Error(message))));
  };

export const postCompanyPost = (companyId, post, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isPosting) return Promise.resolve();
    dispatch(requestPostCompanyPost());
    return postJson(`/companies/${companyId}/posts.json`, state)(null, { post, disclosureType })
      .then(json => dispatch(finishPostCompanyPost('data', json)))
      .catch(({ message }) => dispatch(finishPostCompanyPost(new Error(message))));
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

export const deletePost = postId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isDeleting) return Promise.resolve();
    dispatch(requestDeletePost());
    return deleteJson(`/posts/${postId}.json`, state)()
      .then(() => dispatch(finishDeletePost('data', { postId })))
      .catch(({ message }) => dispatch(finishDeletePost(new Error(message))));
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
    dispatch(requestPostReply());
    return postJson(`/posts/${postId}/replies.json`, state)(null, { post })
      .then(json => dispatch(finishPostReply('data', { postId, reply: json })))
      .catch(({ message }) => dispatch(finishPostReply(new Error(message))));
  };
