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
  REQUEST_CHANGE_OWNER: 'REQUEST_CHANGE_OWNER',
  FINISH_CHANGE_OWNER: 'FINISH_CHANGE_OWNER',
  REQUEST_CHANGE_PARENT_OKR: 'REQUEST_CHANGE_PARENT_OKR',
  FINISH_CHANGE_PARENT_OKR: 'FINISH_CHANGE_PARENT_OKR',
  REQUEST_CHANGE_DISCLOSURE_TYPE: 'REQUEST_CHANGE_DISCLOSURE_TYPE',
  FINISH_CHANGE_DISCLOSURE_TYPE: 'FINISH_CHANGE_DISCLOSURE_TYPE',
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
  requestChangeOwner,
  finishChangeOwner,
  requestChangeParentOkr,
  finishChangeParentOkr,
  requestChangeDisclosureType,
  finishChangeDisclosureType,
} = createActions({
  [Action.FINISH_FETCH_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_POST_KR]: keyValueIdentity,
  [Action.FINISH_PUT_OKR_DETAILS]: keyValueIdentity,
  [Action.FINISH_DELETE_KR]: keyValueIdentity,
  [Action.FINISH_POST_ACHIEVEMENT]: keyValueIdentity,
  [Action.FINISH_CHANGE_OWNER]: keyValueIdentity,
  [Action.FINISH_CHANGE_PARENT_OKR]: keyValueIdentity,
  [Action.FINISH_CHANGE_DISCLOSURE_TYPE]: keyValueIdentity,
},
  Action.REQUEST_FETCH_OKR_DETAILS,
  Action.REQUEST_POST_KR,
  Action.REQUEST_PUT_OKR_DETAILS,
  Action.REQUEST_DELETE_KR,
  Action.REQUEST_POST_ACHIEVEMENT,
  Action.REQUEST_CHANGE_OWNER,
  Action.REQUEST_CHANGE_PARENT_OKR,
  Action.REQUEST_CHANGE_DISCLOSURE_TYPE,
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

export const postKR = kr =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPostingKR) return Promise.resolve();
    dispatch(requestPostKr());
    return postJson('/okrs.json', state)(null, kr)
      .then(json => dispatch(finishPostKr('newKR', { data: json })))
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

export const changeOwner = (id, owner) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isChangingOwner) return Promise.resolve();
    dispatch(requestChangeOwner());
    return putJson(`/okrs/${id}/changeowner.json`, state)(null, mapOwnerOutbound(omit(owner, 'name')))
      .then(() => dispatch(finishChangeOwner('changeOwner', { id, ...mapOwnerOutbound(owner) })))
      .catch(({ message }) => dispatch(finishChangeOwner(new Error(message))));
  };

export const changeParentOkr = (id, newParentOkrId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isChangingParentOkr) return Promise.resolve();
    dispatch(requestChangeParentOkr());
    return putJson(`/okrs/${id}/changeparent.json`, state)(null, { newParentOkrId })
      .then(json => dispatch(finishChangeParentOkr('changeParentOkr', json)))
      .catch(({ message }) => dispatch(finishChangeParentOkr(new Error(message))));
  };

export const changeDisclosureType = (id, disclosureType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isChangingDisclosureType) return Promise.resolve();
    dispatch(requestChangeDisclosureType());
    return putJson(`/okrs/${id}/changedisclosure.json`, state)(null, { disclosureType })
      .then(() => dispatch(finishChangeDisclosureType('changeOwner', { id, disclosureType })))
      .catch(({ message }) => dispatch(finishChangeDisclosureType(new Error(message))));
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

export const postAchievement = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.okr.isPostingAchievement) return Promise.resolve();
    dispatch(requestPostAchievement());
    return postJson(`/okrs/${id}/achievements.json`, state)(null, data)
      .then(json => dispatch(finishPostAchievement('newAchievement', { data: json })))
      .catch(({ message }) => dispatch(finishPostAchievement(new Error(message))));
  };
