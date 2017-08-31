import { createActions } from 'redux-actions';
import { omit } from 'lodash';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson, deleteJson, putJson } from '../../util/ApiUtil';
import { mapOwnerOutbound } from '../../util/OwnerUtil';

export const Action = {
  REQUEST_FETCH_USER_BASICS: 'REQUEST_FETCH_USER_BASICS',
  FINISH_FETCH_USER_BASICS: 'FINISH_FETCH_USER_BASICS',
  REQUEST_FETCH_GROUP_BASICS: 'REQUEST_FETCH_GROUP_BASICS',
  FINISH_FETCH_GROUP_BASICS: 'FINISH_FETCH_GROUP_BASICS',
  REQUEST_FETCH_COMPANY_BASICS: 'REQUEST_FETCH_COMPANY_BASICS',
  FINISH_FETCH_COMPANY_BASICS: 'FINISH_FETCH_COMPANY_BASICS',
  REQUEST_POST_OKR: 'REQUEST_POST_OKR',
  FINISH_POST_OKR: 'FINISH_POST_OKR',
  REQUEST_DELETE_OKR: 'REQUEST_DELETE_OKR',
  FINISH_DELETE_OKR: 'FINISH_DELETE_OKR',
  REQUEST_CHANGE_OKR_OWNER: 'REQUEST_CHANGE_OKR_OWNER',
  FINISH_CHANGE_OKR_OWNER: 'FINISH_CHANGE_OKR_OWNER',
  SYNC_OKR_DETAILS: 'SYNC_OKR_DETAILS',
};

const {
  requestFetchUserBasics,
  finishFetchUserBasics,
  requestFetchGroupBasics,
  finishFetchGroupBasics,
  requestFetchCompanyBasics,
  finishFetchCompanyBasics,
  requestPostOkr,
  finishPostOkr,
  requestDeleteOkr,
  finishDeleteOkr,
  requestChangeOkrOwner,
  finishChangeOkrOwner,
  syncOkrDetails,
} = createActions({
  [Action.FINISH_FETCH_USER_BASICS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_BASICS]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_BASICS]: keyValueIdentity,
  [Action.FINISH_POST_OKR]: keyValueIdentity,
  [Action.FINISH_DELETE_OKR]: keyValueIdentity,
  [Action.FINISH_CHANGE_OKR_OWNER]: keyValueIdentity,
  [Action.SYNC_OKR_DETAILS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_BASICS,
  Action.REQUEST_FETCH_GROUP_BASICS,
  Action.REQUEST_FETCH_COMPANY_BASICS,
  Action.REQUEST_POST_OKR,
  Action.REQUEST_DELETE_OKR,
  Action.REQUEST_CHANGE_OKR_OWNER,
);

const fetchBasics = (subject, node, request, finish) => (id, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isFetching) return Promise.resolve();
    dispatch(request());
    return getJson(`/${subject}/${id}/basics.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finish(node, json)))
      .catch(({ message }) => dispatch(finish(new Error(message))));
  };

export const fetchUserBasics = (id, timeframeId) =>
  fetchBasics('users', 'user', requestFetchUserBasics, finishFetchUserBasics)(id, timeframeId);

export const fetchGroupBasics = (id, timeframeId) =>
  fetchBasics('groups', 'group', requestFetchGroupBasics, finishFetchGroupBasics)(id, timeframeId);

export const fetchCompanyBasics = (id, timeframeId) =>
  fetchBasics('companies', 'company', requestFetchCompanyBasics, finishFetchCompanyBasics)(id, timeframeId);

export const postOkr = (subject, isOwnerCurrent, okr) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isPostingOkr) return Promise.resolve();
    dispatch(requestPostOkr());
    return postJson('/okrs.json', state)(null, okr)
      .then(json => dispatch(finishPostOkr('data', { subject, isOwnerCurrent, data: json })))
      .catch(({ message }) => dispatch(finishPostOkr(new Error(message))));
  };

export const deleteOkr = (subject, id) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isDeletingOkr) return Promise.resolve();
    dispatch(requestDeleteOkr());
    return deleteJson(`/okrs/${id}.json`, state)()
      .then(() => dispatch(finishDeleteOkr('data', { subject, id })))
      .catch(({ message }) => dispatch(finishDeleteOkr(new Error(message))));
  };

export const changeOkrOwner = (subject, id, owner) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isChangingOkrOwner) return Promise.resolve();
    dispatch(requestChangeOkrOwner());
    return putJson(`/okrs/${id}/changeowner.json`, state)(null, mapOwnerOutbound(omit(owner, 'name')))
      .then(() => dispatch(finishChangeOkrOwner('data', { subject, id })))
      .catch(({ message }) => dispatch(finishChangeOkrOwner(new Error(message))));
  };

export const syncOkr = (subject, { payload, error }) =>
  dispatch => (!error ?
    dispatch(syncOkrDetails('syncOkr', { subject, ...payload.data })) :
    dispatch(syncOkrDetails(new Error(payload.message))));
