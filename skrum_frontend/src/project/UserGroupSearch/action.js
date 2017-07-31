import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_USER_GROUPS: 'REQUEST_SEARCH_USER_GROUPS',
  FINISH_SEARCH_USER_GROUPS: 'FINISH_SEARCH_USER_GROUPS',
};

const {
  requestSearchUserGroups,
  finishSearchUserGroups,
} = createActions({
  [Action.FINISH_SEARCH_USER_GROUPS]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_USER_GROUPS,
);

export const searchUserGroups = (userId, keyword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userGroupsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchUserGroups());
    return getJson('/additionalgroups/search.json', state)({ uid: userId, q: keyword })
      .then(json => dispatch(finishSearchUserGroups('data', json)))
      .catch(({ message }) => dispatch(finishSearchUserGroups(new Error(message))));
  };
