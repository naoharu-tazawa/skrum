import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_SEARCH_OKR: 'REQUEST_SEARCH_OKR',
  FINISH_SEARCH_OKR: 'FINISH_SEARCH_OKR',
};

const {
  requestSearchOkr,
  finishSearchOkr,
} = createActions({
  [Action.FINISH_SEARCH_OKR]: keyValueIdentity,
},
  Action.REQUEST_SEARCH_OKR,
);

export const searchOkr = (timeframeId, keyword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okrsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchOkr());
    return getJson('/okrs/search.json', state)({ tfid: timeframeId, q: keyword })
      .then(json => dispatch(finishSearchOkr('data', { keyword, data: json })))
      .catch(({ message }) => dispatch(finishSearchOkr(new Error(message))));
  };

export const searchParentOkr = (ownerType, ownerId, timeframeId, keyword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okrsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchOkr());
    const parameters = { wtype: ownerType, wid: ownerId, tfid: timeframeId, q: keyword };
    return getJson('/parentokrs/search.json', state)(parameters)
      .then(json => dispatch(finishSearchOkr('data', json)))
      .catch(({ message }) => dispatch(finishSearchOkr(new Error(message))));
  };
