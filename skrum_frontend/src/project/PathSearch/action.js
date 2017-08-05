import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_PATH: 'REQUEST_SEARCH_PATH',
  FINISH_SEARCH_PATH: 'FINISH_SEARCH_PATH',
};

const {
  requestSearchPath,
  finishSearchPath,
} = createActions({
  [Action.FINISH_SEARCH_PATH]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_PATH,
);

export const searchPath = (groupId, keyword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.pathsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchPath());
    return getJson('/additionalpaths/search.json', state)({ gid: groupId, q: keyword })
      .then(json => dispatch(finishSearchPath('data', json)))
      .catch(({ message }) => dispatch(finishSearchPath(new Error(message))));
  };
