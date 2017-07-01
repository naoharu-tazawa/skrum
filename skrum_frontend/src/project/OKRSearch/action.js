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
  (dispatch, getStatus) => {
    const status = getStatus();
    if (status.okrsFound.isSearching) return Promise.resolve();
    dispatch(requestSearchOkr());
    return getJson('/okrs/search.json', status)({ tfid: timeframeId, q: keyword })
      .then(json => dispatch(finishSearchOkr('okrsFound', json)))
      .catch(({ message }) => dispatch(finishSearchOkr(new Error(message))));
  };
