import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_GROUP_POSTS: 'REQUEST_FETCH_GROUP_POSTS',
  REQUEST_POST_GROUP_POSTS: 'REQUEST_POST_GROUP_POSTS',
  FINISH_FETCH_GROUP_POSTS: 'FINISH_FETCH_GROUP_POSTS',
  FINISH_POST_GROUP_POSTS: 'FINISH_POST_GROUP_POSTS',
};

const {
  requestFetchGroupPosts,
  requestPostGroupPosts,
  finishFetchGroupPosts,
  finishPostGroupPosts,
} = createActions({
  [Action.FINISH_FETCH_GROUP_POSTS]: keyValueIdentity,
  [Action.FINISH_POST_GROUP_POSTS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_GROUP_POSTS,
  Action.REQUEST_POST_GROUP_POSTS,
);

export const fetchGroupPosts = (groupId, before = '2018-03-03+00:00:00') =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isFetching) return Promise.resolve();
    dispatch(requestFetchGroupPosts());
    return getJson(`/groups/${groupId}/posts.json`, state)({ before })
      .then(json => dispatch(finishFetchGroupPosts('data', json)))
      .catch(({ message }) => dispatch(finishFetchGroupPosts(new Error(message))));
  };

export const postGroupPosts = (groupId, post, disclosureType = '1') =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeline.isPosting) return Promise.resolve();
    dispatch(requestPostGroupPosts());
    return postJson(`/groups/${groupId}/posts.json`, state)(null, { post, disclosureType })
      .then(json => dispatch(finishPostGroupPosts('data', json)))
      .catch(({ message }) => dispatch(finishPostGroupPosts(new Error(message))));
  };
