import { createActions } from 'redux-actions';
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
  REQUEST_CHANGE_OKR_OWNER: 'REQUEST_CHANGE_OKR_OWNER',
  FINISH_CHANGE_OKR_OWNER: 'FINISH_CHANGE_OKR_OWNER',
  REQUEST_BASICS_CHANGE_PARENT_OKR: 'REQUEST_BASICS_CHANGE_PARENT_OKR',
  FINISH_BASICS_CHANGE_PARENT_OKR: 'FINISH_BASICS_CHANGE_PARENT_OKR',
  REQUEST_BASICS_CHANGE_DISCLOSURE_TYPE: 'REQUEST_BASICS_CHANGE_DISCLOSURE_TYPE',
  FINISH_BASICS_CHANGE_DISCLOSURE_TYPE: 'FINISH_BASICS_CHANGE_DISCLOSURE_TYPE',
  REQUEST_BASICS_POST_ACHIEVEMENT: 'REQUEST_BASICS_POST_ACHIEVEMENT',
  FINISH_BASICS_POST_ACHIEVEMENT: 'FINISH_BASICS_POST_ACHIEVEMENT',
  REQUEST_BASICS_SET_RATIOS: 'REQUEST_BASICS_SET_RATIOS',
  FINISH_BASICS_SET_RATIOS: 'FINISH_BASICS_SET_RATIOS',
  REQUEST_COPY_OKR: 'REQUEST_COPY_OKR',
  FINISH_COPY_OKR: 'FINISH_COPY_OKR',
  REQUEST_DELETE_OKR: 'REQUEST_DELETE_OKR',
  FINISH_DELETE_OKR: 'FINISH_DELETE_OKR',
  REQUEST_BASICS_DELETE_KR: 'REQUEST_BASICS_DELETE_KR',
  FINISH_BASICS_DELETE_KR: 'FINISH_BASICS_DELETE_KR',
  SYNC_BASICS_DETAILS: 'SYNC_BASICS_DETAILS',
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
  requestChangeOkrOwner,
  finishChangeOkrOwner,
  requestBasicsChangeParentOkr,
  finishBasicsChangeParentOkr,
  requestBasicsChangeDisclosureType,
  finishBasicsChangeDisclosureType,
  requestBasicsPostAchievement,
  finishBasicsPostAchievement,
  requestBasicsSetRatios,
  finishBasicsSetRatios,
  requestCopyOkr,
  finishCopyOkr,
  requestDeleteOkr,
  finishDeleteOkr,
  requestBasicsDeleteKr,
  finishBasicsDeleteKr,
  syncBasicsDetails,
} = createActions({
  [Action.FINISH_FETCH_USER_BASICS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_BASICS]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_BASICS]: keyValueIdentity,
  [Action.FINISH_POST_OKR]: keyValueIdentity,
  [Action.FINISH_CHANGE_OKR_OWNER]: keyValueIdentity,
  [Action.FINISH_BASICS_CHANGE_PARENT_OKR]: keyValueIdentity,
  [Action.FINISH_BASICS_CHANGE_DISCLOSURE_TYPE]: keyValueIdentity,
  [Action.FINISH_BASICS_POST_ACHIEVEMENT]: keyValueIdentity,
  [Action.FINISH_BASICS_SET_RATIOS]: keyValueIdentity,
  [Action.FINISH_COPY_OKR]: keyValueIdentity,
  [Action.FINISH_DELETE_OKR]: keyValueIdentity,
  [Action.FINISH_BASICS_DELETE_KR]: keyValueIdentity,
  [Action.SYNC_BASICS_DETAILS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_BASICS,
  Action.REQUEST_FETCH_GROUP_BASICS,
  Action.REQUEST_FETCH_COMPANY_BASICS,
  Action.REQUEST_POST_OKR,
  Action.REQUEST_CHANGE_OKR_OWNER,
  Action.REQUEST_BASICS_CHANGE_PARENT_OKR,
  Action.REQUEST_BASICS_CHANGE_DISCLOSURE_TYPE,
  Action.REQUEST_BASICS_POST_ACHIEVEMENT,
  Action.REQUEST_BASICS_SET_RATIOS,
  Action.REQUEST_COPY_OKR,
  Action.REQUEST_DELETE_OKR,
  Action.REQUEST_BASICS_DELETE_KR,
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
      .then(json => dispatch(finishPostOkr('data', { subject, isOwnerCurrent, ...json })))
      .catch(({ message }) => dispatch(finishPostOkr(new Error(message))));
  };

export const changeOkrOwner = (subject, id, owner) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isPutting) return Promise.resolve();
    dispatch(requestChangeOkrOwner());
    const { type, id: ownerId } = owner;
    return putJson(`/okrs/${id}/changeowner.json`, state)(null, mapOwnerOutbound({ type, id: ownerId }))
      .then(() => dispatch(finishChangeOkrOwner('data', { subject, id, ...mapOwnerOutbound(owner) })))
      .catch(({ message }) => dispatch(finishChangeOkrOwner(new Error(message))));
  };

export const changeParentOkr = (subject, id, newParentOkrId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isChangingParentOkr) return Promise.resolve();
    dispatch(requestBasicsChangeParentOkr());
    return putJson(`/okrs/${id}/changeparent.json`, state)(null, { newParentOkrId })
      .then(json => dispatch(finishBasicsChangeParentOkr('data', { subject, ...json })))
      .catch(({ message }) => dispatch(finishBasicsChangeParentOkr(new Error(message))));
  };

export const changeDisclosureType = (subject, id, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isPutting) return Promise.resolve();
    dispatch(requestBasicsChangeDisclosureType());
    return putJson(`/okrs/${id}/changedisclosure.json`, state)(null, { disclosureType })
      .then(() => dispatch(finishBasicsChangeDisclosureType('data', { subject, id, disclosureType })))
      .catch(({ message }) => dispatch(finishBasicsChangeDisclosureType(new Error(message))));
  };

export const postAchievement = (subject, id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isPostingAchievement) return Promise.resolve();
    dispatch(requestBasicsPostAchievement());
    return postJson(`/okrs/${id}/achievements.json`, state)(null, data)
      .then(json => dispatch(finishBasicsPostAchievement('data', { subject, ...json })))
      .catch(({ message }) => dispatch(finishBasicsPostAchievement(new Error(message))));
  };

export const setRatios = (subject, id, ratios) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isSettingRatios) return Promise.resolve();
    dispatch(requestBasicsSetRatios());
    return putJson(`/okrs/${id}/setratio.json`, state)(null, ratios)
      .then(json => dispatch(finishBasicsSetRatios('data', { subject, ...json, ratios })))
      .catch(({ message }) => dispatch(finishBasicsSetRatios(new Error(message))));
  };

export const copyOkr = (subject, id, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isCopyingOkr) return Promise.resolve();
    dispatch(requestCopyOkr());
    return postJson(`/okrs/${id}/clone.json`, state)(null, { timeframeId })
      .then(json => dispatch(finishCopyOkr('data', { subject, ...json })))
      .catch(({ message }) => dispatch(finishCopyOkr(new Error(message))));
  };

export const deleteOkr = (subject, id) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isDeletingOkr) return Promise.resolve();
    dispatch(requestDeleteOkr());
    return deleteJson(`/okrs/${id}.json`, state)()
      .then(json => dispatch(finishDeleteOkr('data', { subject, id, ...json })))
      .catch(({ message }) => dispatch(finishDeleteOkr(new Error(message))));
  };

export const deleteKR = (subject, id) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.basics.isDeletingKR) return Promise.resolve();
    dispatch(requestBasicsDeleteKr());
    return deleteJson(`/okrs/${id}.json`, state)()
      .then(json => dispatch(finishBasicsDeleteKr('data', { subject, id, ...json })))
      .catch(({ message }) => dispatch(finishBasicsDeleteKr(new Error(message))));
  };

export const syncOkr = (subject, { payload, error }) =>
  dispatch => (error ? ({ payload, error }) :
    dispatch(syncBasicsDetails('data', { subject, ...payload.data })));

export const syncParentOkr = (subject, { payload, error }) =>
  dispatch => (error ? ({ payload, error }) :
    dispatch(finishBasicsChangeParentOkr('data', { subject, ...payload.data })));

export const syncNewKR = (subject, { payload, error }) =>
  dispatch => (error ? ({ payload, error }) :
    dispatch(finishPostOkr('data', { subject, ...payload.data })));

export const syncAchievement = (subject, { payload, error }) =>
  dispatch => (error ? ({ payload, error }) :
    dispatch(finishBasicsPostAchievement('data', { subject, ...payload.data })));

export const syncRatios = (subject, { payload, error }) =>
  dispatch => (error ? ({ payload, error }) :
    dispatch(finishBasicsSetRatios('data', { subject, ...payload.data })));
