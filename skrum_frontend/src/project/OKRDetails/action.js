import { createActions } from 'redux-actions';
import { omit } from 'lodash';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson, postJson, deleteJson } from '../../util/ApiUtil';
import { mapOwnerOutbound } from '../../util/OwnerUtil';

export const Action = {
  REQUEST_FETCH_OKR_DETAILS: 'REQUEST_FETCH_OKR_DETAILS',
  FINISH_FETCH_OKR_DETAILS: 'FINISH_FETCH_OKR_DETAILS',
  REQUEST_POST_KR: 'REQUEST_POST_KR',
  FINISH_POST_KR: 'FINISH_POST_KR',
  REQUEST_PUT_OKR_DETAILS: 'REQUEST_PUT_OKR_DETAILS',
  FINISH_PUT_OKR_DETAILS: 'FINISH_PUT_OKR_DETAILS',
  REQUEST_DELETE_KR: 'REQUEST_DELETE_KR',
  FINISH_DELETE_KR: 'FINISH_DELETE_KR',
  REQUEST_POST_ACHIEVEMENT: 'REQUEST_POST_ACHIEVEMENT',
  FINISH_POST_ACHIEVEMENT: 'FINISH_POST_ACHIEVEMENT',
  REQUEST_CHANGE_KR_OWNER: 'REQUEST_CHANGE_KR_OWNER',
  FINISH_CHANGE_KR_OWNER: 'FINISH_CHANGE_KR_OWNER',
  REQUEST_CHANGE_PARENT_OKR: 'REQUEST_CHANGE_PARENT_OKR',
  FINISH_CHANGE_PARENT_OKR: 'FINISH_CHANGE_PARENT_OKR',
  REQUEST_CHANGE_OKR_DISCLOSURE_TYPE: 'REQUEST_CHANGE_OKR_DISCLOSURE_TYPE',
  FINISH_CHANGE_OKR_DISCLOSURE_TYPE: 'FINISH_CHANGE_OKR_DISCLOSURE_TYPE',
  REQUEST_SET_RATIOS: 'REQUEST_SET_RATIOS',
  FINISH_SET_RATIOS: 'FINISH_SET_RATIOS',
};

const {
  requestFetchOkrDetails,
  finishFetchOkrDetails,
  requestPostKr,
  finishPostKr,
  requestPutOkrDetails,
  finishPutOkrDetails,
  requestDeleteKr,
  finishDeleteKr,
  requestPostAchievement,
  finishPostAchievement,
  requestChangeKrOwner,
  finishChangeKrOwner,
  requestChangeParentOkr,
  finishChangeParentOkr,
  requestChangeOkrDisclosureType,
  finishChangeOkrDisclosureType,
  requestSetRatios,
  finishSetRatios,
} = createActions({
  [Action.FINISH_FETCH_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_POST_KR]: keyValueIdentity,
  [Action.FINISH_PUT_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_DELETE_KR]: keyValueIdentity,
  [Action.FINISH_POST_ACHIEVEMENT]: keyValueIdentity,
  [Action.FINISH_CHANGE_KR_OWNER]: keyValueIdentity,
  [Action.FINISH_CHANGE_PARENT_OKR]: keyValueIdentity,
  [Action.FINISH_CHANGE_OKR_DISCLOSURE_TYPE]: keyValueIdentity,
  [Action.FINISH_SET_RATIOS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_OKR_DETAILS,
  Action.REQUEST_POST_KR,
  Action.REQUEST_PUT_OKR_DETAILS,
  Action.REQUEST_DELETE_KR,
  Action.REQUEST_POST_ACHIEVEMENT,
  Action.REQUEST_CHANGE_KR_OWNER,
  Action.REQUEST_CHANGE_PARENT_OKR,
  Action.REQUEST_CHANGE_OKR_DISCLOSURE_TYPE,
  Action.REQUEST_SET_RATIOS,
);

export const fetchOKRDetails = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isFetching) return Promise.resolve();
    dispatch(requestFetchOkrDetails());
    return getJson(`/okrs/${id}/details.json`, state)()
      .then(json => dispatch(finishFetchOkrDetails('data', json)))
      .catch(({ message }) => dispatch(finishFetchOkrDetails(new Error(message))));
  };

export const postKR = kr =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPostingKR) return Promise.resolve();
    dispatch(requestPostKr());
    return postJson('/okrs.json', state)(null, kr)
      .then(json => dispatch(finishPostKr('data', json)))
      .catch(({ message }) => dispatch(finishPostKr(new Error(message))));
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

export const changeKROwner = (id, owner) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPutting) return Promise.resolve();
    dispatch(requestChangeKrOwner());
    return putJson(`/okrs/${id}/changeowner.json`, state)(null, mapOwnerOutbound(omit(owner, 'name')))
      .then(() => dispatch(finishChangeKrOwner('data', { id, ...mapOwnerOutbound(owner) })))
      .catch(({ message }) => dispatch(finishChangeKrOwner(new Error(message))));
  };

export const changeParentOkr = (id, newParentOkrId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isChangingParentOkr) return Promise.resolve();
    dispatch(requestChangeParentOkr());
    return putJson(`/okrs/${id}/changeparent.json`, state)(null, { newParentOkrId })
      .then(json => dispatch(finishChangeParentOkr('data', json)))
      .catch(({ message }) => dispatch(finishChangeParentOkr(new Error(message))));
  };

export const changeDisclosureType = (id, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPutting) return Promise.resolve();
    dispatch(requestChangeOkrDisclosureType());
    return putJson(`/okrs/${id}/changedisclosure.json`, state)(null, { disclosureType })
      .then(() => dispatch(finishChangeOkrDisclosureType('data', { id, disclosureType })))
      .catch(({ message }) => dispatch(finishChangeOkrDisclosureType(new Error(message))));
  };

export const deleteKR = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isDeletingKR) return Promise.resolve();
    dispatch(requestDeleteKr());
    return deleteJson(`/okrs/${id}.json`, state)()
      .then(json => dispatch(finishDeleteKr('data', { id, ...json })))
      .catch(({ message }) => dispatch(finishDeleteKr(new Error(message))));
  };

export const postAchievement = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPostingAchievement) return Promise.resolve();
    dispatch(requestPostAchievement());
    return postJson(`/okrs/${id}/achievements.json`, state)(null, data)
      .then(json => dispatch(finishPostAchievement('data', json)))
      .catch(({ message }) => dispatch(finishPostAchievement(new Error(message))));
  };

export const setRatios = (id, ratios) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isSettingRatios) return Promise.resolve();
    dispatch(requestSetRatios());
    return putJson(`/okrs/${id}/setratio.json`, state)(null, ratios)
      .then(json => dispatch(finishSetRatios('data', { ...json, ratios })))
      .catch(({ message }) => dispatch(finishSetRatios(new Error(message))));
  };
