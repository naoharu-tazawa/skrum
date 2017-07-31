import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_GROUP_USERS: 'REQUEST_SEARCH_GROUP_USERS',
  FINISH_SEARCH_GROUP_USERS: 'FINISH_SEARCH_GROUP_USERS',
};

const {
  requestSearchGroupUsers,
  finishSearchGroupUsers,
} = createActions({
  [Action.FINISH_SEARCH_GROUP_USERS]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_GROUP_USERS,
);

export const searchGroupUsers = (groupId, keyword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupUsersFound.isSearching) return Promise.resolve();
    dispatch(requestSearchGroupUsers());
    return getJson('/additionalusers/search.json', state)({ gid: groupId, q: keyword })
      .then(json => dispatch(finishSearchGroupUsers('data', json)))
      .catch(({ message }) => dispatch(finishSearchGroupUsers(new Error(message))));
  };
