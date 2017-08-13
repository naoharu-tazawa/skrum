import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson, postJson, deleteJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY_TIMEFRAMES: 'REQUEST_FETCH_COMPANY_TIMEFRAMES',
  FINISH_FETCH_COMPANY_TIMEFRAMES: 'FINISH_FETCH_COMPANY_TIMEFRAMES',
  REQUEST_PUT_TIMEFRAME: 'REQUEST_PUT_TIMEFRAME',
  FINISH_PUT_TIMEFRAME: 'FINISH_PUT_TIMEFRAME',
  REQUEST_POST_TIMEFRAME: 'REQUEST_POST_TIMEFRAME',
  FINISH_POST_TIMEFRAME: 'FINISH_POST_TIMEFRAME',
  REQUEST_DEFAULT_TIMEFRAME: 'REQUEST_DEFAULT_TIMEFRAME',
  FINISH_DEFAULT_TIMEFRAME: 'FINISH_DEFAULT_TIMEFRAME',
  REQUEST_DELETE_TIMEFRAME: 'REQUEST_DELETE_TIMEFRAME',
  FINISH_DELETE_TIMEFRAME: 'FINISH_DELETE_TIMEFRAME',
};

const {
  requestFetchCompanyTimeframes,
  finishFetchCompanyTimeframes,
  requestPutTimeframe,
  finishPutTimeframe,
  requestPostTimeframe,
  finishPostTimeframe,
  requestDefaultTimeframe,
  finishDefaultTimeframe,
  requestDeleteTimeframe,
  finishDeleteTimeframe,
} = createActions({
  [Action.FINISH_FETCH_COMPANY_TIMEFRAMES]: keyValueIdentity,
  [Action.FINISH_PUT_TIMEFRAME]: keyValueIdentity,
  [Action.FINISH_POST_TIMEFRAME]: keyValueIdentity,
  [Action.FINISH_DEFAULT_TIMEFRAME]: keyValueIdentity,
  [Action.FINISH_DELETE_TIMEFRAME]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY_TIMEFRAMES,
  Action.REQUEST_PUT_TIMEFRAME,
  Action.REQUEST_POST_TIMEFRAME,
  Action.REQUEST_DEFAULT_TIMEFRAME,
  Action.REQUEST_DELETE_TIMEFRAME,
);

export const fetchCompanyTimeframes = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompanyTimeframes());
    return getJson(`/companies/${companyId}/timeframedetails.json`, state)()
      .then(json => dispatch(finishFetchCompanyTimeframes('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompanyTimeframes(new Error(message))));
  };

export const putTimeframe = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isPutting) return Promise.resolve();
    dispatch(requestPutTimeframe());
    return putJson(`/timeframes/${id}.json`, state)(null, data)
      .then(() => dispatch(finishPutTimeframe('data', { id, ...data })))
      .catch(({ message }) => dispatch(finishPutTimeframe(new Error(message))));
  };

export const postTimeframe = data =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isPosting) return Promise.resolve();
    dispatch(requestPostTimeframe());
    return postJson('/timeframes.json', state)(null, data)
      .then(json => dispatch(finishPostTimeframe('data', { data: json })))
      .catch(({ message }) => dispatch(finishPostTimeframe(new Error(message))));
  };

export const defaultTimeframe = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isDefaulting) return Promise.resolve();
    dispatch(requestDefaultTimeframe());
    return putJson(`/timeframes/${id}/setdefault.json`, state)()
      .then(() => dispatch(finishDefaultTimeframe('data', { id })))
      .catch(({ message }) => dispatch(finishDefaultTimeframe(new Error(message))));
  };

export const deleteTimeframe = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isDeleting) return Promise.resolve();
    dispatch(requestDeleteTimeframe());
    return deleteJson(`/timeframes/${id}.json`, state)()
      .then(() => dispatch(finishDeleteTimeframe('data', { id })))
      .catch(({ message }) => dispatch(finishDeleteTimeframe(new Error(message))));
  };
