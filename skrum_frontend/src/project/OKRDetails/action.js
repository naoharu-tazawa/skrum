import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson, postJson, deleteJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_OKR_DETAILS: 'REQUEST_FETCH_OKR_DETAILS',
  FINISH_FETCH_OKR_DETAILS: 'FINISH_FETCH_OKR_DETAILS',
  REQUEST_PUT_OKR_DETAILS: 'REQUEST_PUT_OKR_DETAILS',
  FINISH_PUT_OKR_DETAILS: 'FINISH_PUT_OKR_DETAILS',
  REQUEST_POST_KR: 'REQUEST_POST_KR',
  FINISH_POST_KR: 'FINISH_POST_KR',
  REQUEST_DELETE_KR: 'REQUEST_DELETE_KR',
  FINISH_DELETE_KR: 'FINISH_DELETE_KR',
};

const {
  requestFetchOkrDetails,
  finishFetchOkrDetails,
  requestPutOkrDetails,
  finishPutOkrDetails,
  requestPostKr,
  finishPostKr,
  requestDeleteKr,
  finishDeleteKr,
} = createActions({
  [Action.FINISH_FETCH_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_PUT_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_POST_KR]: keyValueIdentity,
  [Action.FINISH_DELETE_KR]: keyValueIdentity,
},
  Action.REQUEST_FETCH_OKR_DETAILS,
  Action.REQUEST_PUT_OKR_DETAILS,
  Action.REQUEST_POST_KR,
  Action.REQUEST_DELETE_KR,
);

export const fetchOKRDetails = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isFetching) return Promise.resolve();
    dispatch(requestFetchOkrDetails());
    return getJson(`/okrs/${id}/details.json`, state)()
      .then(json => dispatch(finishFetchOkrDetails('okr', json)))
      .catch(({ message }) => dispatch(finishFetchOkrDetails(new Error(message))));
  };

export const putOKR = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPutting) return Promise.resolve();
    dispatch(requestPutOkrDetails());
    return putJson(`/okrs/${id}.json`, state)(null, data)
      .then(() => dispatch(finishPutOkrDetails('data', { id, ...data })))
      .catch(({ message }) => dispatch(finishPutOkrDetails(new Error(message))));
  };

export const postKR = kr =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPostingKR) return Promise.resolve();
    dispatch(requestPostKr());
    return postJson('/okrs.json', state)(null, kr)
      .then(json => dispatch(finishPostKr('newKR', { data: json })))
      .catch(({ message }) => dispatch(finishPostKr(new Error(message))));
  };

export const deleteKR = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isDeletingKR) return Promise.resolve();
    dispatch(requestDeleteKr());
    return deleteJson(`/okrs/${id}.json`, state)()
      .then(() => dispatch(finishDeleteKr('deletedKR', { id })))
      .catch(({ message }) => dispatch(finishDeleteKr(new Error(message))));
  };
