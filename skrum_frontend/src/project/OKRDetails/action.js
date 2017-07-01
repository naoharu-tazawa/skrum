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
  [Action.REQUEST_PUT_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_POST_KR]: keyValueIdentity,
  [Action.FINISH_DELETE_KR]: keyValueIdentity,
},
  Action.REQUEST_FETCH_OKR_DETAILS,
  Action.REQUEST_POST_KR,
  Action.REQUEST_DELETE_KR,
);

export const fetchOKRDetails = id =>
  (dispatch, getStatus) => {
    const status = getStatus();
    if (status.okr.isFetching) return Promise.resolve();
    dispatch(requestFetchOkrDetails());
    return getJson(`/okrs/${id}/details.json`, status)()
      .then(json => dispatch(finishFetchOkrDetails('okr', json)))
      .catch(({ message }) => dispatch(finishFetchOkrDetails(new Error(message))));
  };

export const putOKR = (id, data) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    if (status.okr.isPutting) return Promise.resolve();
    dispatch(requestPutOkrDetails('data', { id: Number(id), ...data }));
    return putJson(`/okrs/${id}.json`, status)(null, data)
      .then(json => dispatch(finishPutOkrDetails('data', json)))
      .catch(({ message }) => dispatch(finishPutOkrDetails(new Error(message))));
  };

export function postKR(kr, completion) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    if (status.okr.isPostingKR) return Promise.resolve();
    dispatch(requestPostKr());
    return postJson('/okrs.json', status)(null, kr)
      .then((json) => {
        dispatch(finishPostKr('newKR', { data: json }));
        if (completion) completion({});
      })
      .catch(({ message }) => {
        dispatch(finishPostKr(new Error(message)));
        if (completion) completion({ error: message });
      });
  };
}

export const deleteKR = (id, completion) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    if (status.okr.isDeletingKR) return Promise.resolve();
    dispatch(requestDeleteKr());
    return deleteJson(`/okrs/${id}.json`, status)()
      .then(() => {
        dispatch(finishDeleteKr('deletedKR', { id }));
        if (completion) completion({});
      })
      .catch(({ message }) => {
        dispatch(finishDeleteKr(new Error(message)));
        if (completion) completion({ error: message });
      });
  };
