import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_GROUP_POSTS: 'REQUEST_FETCH_GROUP_POSTS',
  FINISH_FETCH_GROUP_POSTS: 'FINISH_FETCH_GROUP_POSTS',
};

const {
  requestFetchGroupPosts,
  finishFetchGroupPosts,
} = createActions({
  [Action.FINISH_FETCH_GROUP_POSTS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_GROUP_POSTS,
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
