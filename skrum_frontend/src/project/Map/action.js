import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_OKRS: 'REQUEST_FETCH_USER_OKRS',
  REQUEST_FETCH_GROUP_OKRS: 'REQUEST_FETCH_GROUP_OKRS',
  REQUEST_FETCH_COMPANY_OKRS: 'REQUEST_FETCH_COMPANY_OKRS',
  REQUEST_FETCH_OKR_DESCENDANTS: 'REQUEST_FETCH_OKR_DESCENDANTS',
  FINISH_FETCH_USER_OKRS: 'FINISH_FETCH_USER_OKRS',
  FINISH_FETCH_GROUP_OKRS: 'FINISH_FETCH_GROUP_OKRS',
  FINISH_FETCH_COMPANY_OKRS: 'FINISH_FETCH_COMPANY_OKRS',
  FINISH_FETCH_OKR_DESCENDANTS: 'FINISH_FETCH_OKR_DESCENDANTS',
};

const {
  requestFetchUserOkrs,
  requestFetchGroupOkrs,
  requestFetchCompanyOkrs,
  requestFetchOkrDescendants,
  finishFetchUserOkrs,
  finishFetchGroupOkrs,
  finishFetchCompanyOkrs,
  finishFetchOkrDescendants,
} = createActions({
  [Action.FINISH_FETCH_USER_OKRS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_OKRS]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_OKRS]: keyValueIdentity,
  [Action.FINISH_FETCH_OKR_DESCENDANTS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_OKRS,
  Action.REQUEST_FETCH_GROUP_OKRS,
  Action.REQUEST_FETCH_COMPANY_OKRS,
  Action.REQUEST_FETCH_OKR_DESCENDANTS,
);

const fetchMap = (subject, node, request, finish) => (id, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.map.isFetching) return Promise.resolve();
    dispatch(request());
    return getJson(`/${subject}/${id}/okrs.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finish(node, json)))
      .catch(({ message }) => dispatch(finish(new Error(message))));
  };

const fetchSpecificOkrMap = (node, request, finish) => (timeframeId, okrId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.map.isFetching) return Promise.resolve();
    dispatch(request());
    return getJson(`/okrs/${okrId}/descendants.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finish(node, json)))
      .catch(({ message }) => dispatch(finish(new Error(message))));
  };

export const fetchUserOkrs = (id, timeframeId) =>
  fetchMap('users', 'user', requestFetchUserOkrs, finishFetchUserOkrs)(id, timeframeId);

export const fetchGroupOkrs = (id, timeframeId) =>
  fetchMap('groups', 'group', requestFetchGroupOkrs, finishFetchGroupOkrs)(id, timeframeId);

export const fetchCompanyOkrs = (id, timeframeId) =>
  fetchMap('companies', 'company', requestFetchCompanyOkrs, finishFetchCompanyOkrs)(id, timeframeId);

export const fetchOkrDescendants = (node, timeframeId, okrId) =>
  // eslint-disable-next-line max-len
  fetchSpecificOkrMap(node, requestFetchOkrDescendants, finishFetchOkrDescendants)(timeframeId, okrId);
