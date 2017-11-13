import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson } from '../../util/ApiUtil';
import { toUrlDateParam } from '../../util/DatetimeUtil';

export const Action = {
  REQUEST_FETCH_ONE_ON_ONE_NOTES: 'REQUEST_FETCH_ONE_ON_ONE_NOTES',
  FINISH_FETCH_ONE_ON_ONE_NOTES: 'FINISH_FETCH_ONE_ON_ONE_NOTES',
  REQUEST_MORE_ONE_ON_ONE_NOTES: 'REQUEST_MORE_ONE_ON_ONE_NOTES',
  FINISH_MORE_ONE_ON_ONE_NOTES: 'FINISH_MORE_ONE_ON_ONE_NOTES',
  REQUEST_FETCH_ONE_ON_ONE_QUERY: 'REQUEST_FETCH_ONE_ON_ONE_QUERY',
  FINISH_FETCH_ONE_ON_ONE_QUERY: 'FINISH_FETCH_ONE_ON_ONE_QUERY',
  REQUEST_MORE_ONE_ON_ONE_QUERY: 'REQUEST_MORE_ONE_ON_ONE_QUERY',
  FINISH_MORE_ONE_ON_ONE_QUERY: 'FINISH_MORE_ONE_ON_ONE_QUERY',
  REQUEST_FETCH_ONE_ON_ONE_DIALOG: 'REQUEST_FETCH_ONE_ON_ONE_DIALOG',
  FINISH_FETCH_ONE_ON_ONE_DIALOG: 'FINISH_FETCH_ONE_ON_ONE_DIALOG',
  REQUEST_POST_ONE_ON_ONE_NOTE: 'REQUEST_POST_ONE_ON_ONE_NOTE',
  FINISH_POST_ONE_ON_ONE_NOTE: 'FINISH_POST_ONE_ON_ONE_NOTE',
  REQUEST_POST_ONE_ON_ONE_REPLY: 'REQUEST_POST_ONE_ON_ONE_REPLY',
  FINISH_POST_ONE_ON_ONE_REPLY: 'FINISH_POST_ONE_ON_ONE_REPLY',
};

const {
  requestFetchOneOnOneNotes,
  finishFetchOneOnOneNotes,
  requestMoreOneOnOneNotes,
  finishMoreOneOnOneNotes,
  requestFetchOneOnOneQuery,
  finishFetchOneOnOneQuery,
  requestMoreOneOnOneQuery,
  finishMoreOneOnOneQuery,
  requestFetchOneOnOneDialog,
  finishFetchOneOnOneDialog,
  requestPostOneOnOneNote,
  finishPostOneOnOneNote,
  requestPostOneOnOneReply,
  finishPostOneOnOneReply,
} = createActions({
  [Action.FINISH_FETCH_ONE_ON_ONE_NOTES]: keyValueIdentity,
  [Action.FINISH_MORE_ONE_ON_ONE_NOTES]: keyValueIdentity,
  [Action.FINISH_FETCH_ONE_ON_ONE_QUERY]: keyValueIdentity,
  [Action.FINISH_MORE_ONE_ON_ONE_QUERY]: keyValueIdentity,
  [Action.FINISH_FETCH_ONE_ON_ONE_DIALOG]: keyValueIdentity,
  [Action.FINISH_POST_ONE_ON_ONE_NOTE]: keyValueIdentity,
  [Action.FINISH_POST_ONE_ON_ONE_REPLY]: keyValueIdentity,
},
  Action.REQUEST_FETCH_ONE_ON_ONE_NOTES,
  Action.REQUEST_MORE_ONE_ON_ONE_NOTES,
  Action.REQUEST_FETCH_ONE_ON_ONE_QUERY,
  Action.REQUEST_MORE_ONE_ON_ONE_QUERY,
  Action.REQUEST_FETCH_ONE_ON_ONE_DIALOG,
  Action.REQUEST_POST_ONE_ON_ONE_NOTE,
  Action.REQUEST_POST_ONE_ON_ONE_REPLY,
);

export const fetchOneOnOneNotes = (userId, type) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isFetching) return Promise.resolve();
    dispatch(requestFetchOneOnOneNotes());
    return getJson(`/users/${userId}/oneonones.json`, state)({ oootype: type })
      .then(json => dispatch(finishFetchOneOnOneNotes('data', { notes: json, type })))
      .catch(({ message }) => dispatch(finishFetchOneOnOneNotes(new Error(message))));
  };

export const fetchMoreOneOnOneNotes = (userId, type, before) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isFetchingMore) return Promise.resolve();
    dispatch(requestMoreOneOnOneNotes());
    return getJson(`/users/${userId}/oneonones.json`, state)({ oootype: type, before })
      .then(json => dispatch(finishMoreOneOnOneNotes('data', { notes: json })))
      .catch(({ message }) => dispatch(finishMoreOneOnOneNotes(new Error(message))));
  };

export const queryOneOnOneNotes = (userId, query) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isQuerying) return Promise.resolve();
    dispatch(requestFetchOneOnOneQuery());
    const { startDate, endDate, keyword } = query;
    const parameters = {
      ...startDate && { sdate: toUrlDateParam(startDate) },
      ...endDate && { edate: toUrlDateParam(endDate) },
      q: keyword,
    };
    return getJson(`/users/${userId}/newoneonones.json`, state)(parameters)
      .then(json => dispatch(finishFetchOneOnOneQuery('data', { ...json, query })))
      .catch(({ message }) => dispatch(finishFetchOneOnOneQuery(new Error(message))));
  };

export const queryMoreOneOnOneNotes = (userId, { startDate, endDate, keyword }, before) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isQueryingMore) return Promise.resolve();
    dispatch(requestMoreOneOnOneQuery());
    const parameters = {
      sdate: toUrlDateParam(startDate),
      edate: toUrlDateParam(endDate),
      q: keyword,
      before,
    };
    return getJson(`/users/${userId}/newoneonones.json`, state)(parameters)
      .then(json => dispatch(finishMoreOneOnOneQuery('data', json)))
      .catch(({ message }) => dispatch(finishMoreOneOnOneQuery(new Error(message))));
  };

export const fetchOneOnOneDialog = (userId, id) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isFetchingDialog) return Promise.resolve();
    dispatch(requestFetchOneOnOneDialog());
    return getJson(`/users/${userId}/oneonones/${id}.json`, state)()
      .then(json => dispatch(finishFetchOneOnOneDialog('data', { id, ...json })))
      .catch(({ message }) => dispatch(finishFetchOneOnOneDialog(new Error(message))));
  };

export const postOneOnOneNote = (userId, { oneOnOneType, ...entry }) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isPostingNote) return Promise.resolve();
    dispatch(requestPostOneOnOneNote());
    const apiMap = { 1: 'reports', 2: 'reports', 3: 'feedbacks', 4: 'feedbacks', 5: 'interviewnotes' };
    const apiName = apiMap[oneOnOneType];
    return postJson(`/users/${userId}/${apiName}.json`, state)(null, { oneOnOneType, ...entry })
      .then(json => dispatch(finishPostOneOnOneNote('data', json)))
      .catch(({ message }) => dispatch(finishPostOneOnOneNote(new Error(message))));
  };

export const postOneOnOneReply = (id, reply) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.oneOnOne.isPostingReply) return Promise.resolve();
    dispatch(requestPostOneOnOneReply());
    return postJson(`/oneonones/${id}/replies.json`, state)(null, { reply })
      .then(json => dispatch(finishPostOneOnOneReply('data', json)))
      .catch(({ message }) => dispatch(finishPostOneOnOneReply(new Error(message))));
  };
