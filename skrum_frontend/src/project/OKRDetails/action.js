import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_OKR_DETAILS: 'REQUEST_FETCH_OKR_DETAILS',
  FINISH_FETCH_OKR_DETAILS: 'FINISH_FETCH_OKR_DETAILS',
  REQUEST_PUT_OKR_DETAILS: 'REQUEST_PUT_OKR_DETAILS',
  FINISH_PUT_OKR_DETAILS: 'FINISH_PUT_OKR_DETAILS',
};

const {
  requestFetchOkrDetails,
  finishFetchOkrDetails,
  requestPutOkrDetails,
  finishPutOkrDetails,
} = createActions({
  [Action.FINISH_FETCH_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_PUT_OKR_DETAILS]: keyValueIdentity,
  [Action.REQUEST_PUT_OKR_DETAILS]: keyValueIdentity,
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
      .catch(({ message }) => dispatch(finishFetchOkrDetails(new Error(message))));
  };

export const putOKR = (id, data) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isPutting } = status.okr;
    if (isPutting) {
      return Promise.resolve();
    }
    dispatch(requestPutOkrDetails('data', { id: Number(id), ...data }));
    return putJson(`/okrs/${id}.json`, status)(null, data)
      .then(json => dispatch(finishPutOkrDetails('data', json)))
      .catch(({ message }) => dispatch(finishPutOkrDetails(new Error(message))));
  };
