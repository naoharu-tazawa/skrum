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

export function fetchGroupPosts(groupId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.timeline;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchGroupPosts());
    return getJson(`/groups/${groupId}/posts.json`, status)()
      .then(json => dispatch(finishFetchGroupPosts('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchGroupPosts(new Error(message)));
      });
  };
}

export function postGroupPosts(groupId, post, disclosureType = '1') {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isPosting } = status.timeline;
    if (isPosting) {
      return Promise.resolve();
    }
    dispatch(requestPostGroupPosts());
    return postJson(`/groups/${groupId}/posts.json`, status)(null, { post, disclosureType })
      .then(json => dispatch(finishPostGroupPosts('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishPostGroupPosts(new Error(message)));
      });
  };
}
