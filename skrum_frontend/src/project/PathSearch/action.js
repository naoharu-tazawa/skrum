import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_PATH: 'REQUEST_SEARCH_PATH',
  FINISH_SEARCH_PATH: 'FINISH_SEARCH_PATH',
  REQUEST_SEARCH_ADDITIONAL_PATH: 'REQUEST_SEARCH_ADDITIONAL_PATH',
  FINISH_SEARCH_ADDITIONAL_PATH: 'FINISH_SEARCH_ADDITIONAL_PATH',
};

const {
  requestSearchPath,
  finishSearchPath,
  requestSearchAdditionalPath,
  finishSearchAdditionalPath,
} = createActions({
  [Action.FINISH_SEARCH_PATH]: keyValueIdentity,
  [Action.FINISH_SEARCH_ADDITIONAL_PATH]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_PATH,
  Action.REQUEST_SEARCH_ADDITIONAL_PATH,
);

export const searchPath = keyword =>
  (dispatch, getState) => {
    const state = getState();
    if (state.pathsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchPath());
    return getJson('/paths/search.json', state)({ q: keyword })
      .then(json => dispatch(finishSearchPath('data', json)))
      .catch(({ message }) => dispatch(finishSearchPath(new Error(message))));
  };

export const searchAdditionalPath = (groupId, keyword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.pathsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchAdditionalPath());
    return getJson('/additionalpaths/search.json', state)({ gid: groupId, q: keyword })
      .then(json => dispatch(finishSearchAdditionalPath('data', json)))
      .catch(({ message }) => dispatch(finishSearchAdditionalPath(new Error(message))));
  };
