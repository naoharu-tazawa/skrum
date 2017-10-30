import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_USER: 'REQUEST_SEARCH_USER',
  FINISH_SEARCH_USER: 'FINISH_SEARCH_USER',
};

const {
  requestSearchUser,
  finishSearchUser,
} = createActions({
  [Action.FINISH_SEARCH_USER]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_USER,
);

export const searchUser = keyword =>
  (dispatch, getState) => {
    const state = getState();
    if (state.usersFound.isSearching) return Promise.resolve();
    dispatch(requestSearchUser());
    return getJson('/users/search.json', state)({ q: keyword })
      .then(json => dispatch(finishSearchUser('data', { keyword, data: json })))
      .catch(({ message }) => dispatch(finishSearchUser(new Error(message))));
  };
