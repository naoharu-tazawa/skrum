import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_OWNER: 'REQUEST_SEARCH_OWNER',
  FINISH_SEARCH_OWNER: 'FINISH_SEARCH_OWNER',
};

const {
  requestSearchOwner,
  finishSearchOwner,
} = createActions({
  [Action.FINISH_SEARCH_OWNER]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_OWNER,
);

export const searchOwner = keyword =>
  (dispatch, getState) => {
    const state = getState();
    if (state.ownersFound.isSearching) return Promise.resolve();
    dispatch(requestSearchOwner());
    return getJson('/owners/search.json', state)({ q: keyword })
      .then(json => dispatch(finishSearchOwner('data', { keyword, data: json })))
      .catch(({ message }) => dispatch(finishSearchOwner(new Error(message))));
  };
