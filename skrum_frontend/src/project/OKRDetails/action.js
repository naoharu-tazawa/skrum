import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_OKR_DETAILS: 'REQUEST_FETCH_OKR_DETAILS',
  FINISH_FETCH_OKR_DETAILS: 'FINISH_FETCH_OKR_DETAILS',
};

const {
  requestFetchOkrDetails,
  finishFetchOkrDetails,
} = createActions({
  [Action.FINISH_FETCH_OKR_DETAILS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_OKR_DETAILS,
);

export const fetchOKRDetails = id =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.okr;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchOkrDetails());
    return getJson(`/okrs/${id}/details.json`, status)()
      .then(json => dispatch(finishFetchOkrDetails('okr', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchOkrDetails(new Error(message)));
      });
  };
