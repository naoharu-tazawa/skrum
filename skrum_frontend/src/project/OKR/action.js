import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_BASICS: 'REQUEST_FETCH_USER_BASICS',
  REQUEST_FETCH_GROUP_BASICS: 'REQUEST_FETCH_GROUP_BASICS',
  REQUEST_FETCH_COMPANY_BASICS: 'REQUEST_FETCH_COMPANY_BASICS',
  FINISH_FETCH_USER_BASICS: 'FINISH_FETCH_USER_BASICS',
  FINISH_FETCH_GROUP_BASICS: 'FINISH_FETCH_GROUP_BASICS',
  FINISH_FETCH_COMPANY_BASICS: 'FINISH_FETCH_COMPANY_BASICS',
};

const {
  requestFetchUserBasics,
  requestFetchGroupBasics,
  requestFetchCompanyBasics,
  finishFetchUserBasics,
  finishFetchGroupBasics,
  finishFetchCompanyBasics,
} = createActions({
  [Action.FINISH_FETCH_USER_BASICS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_BASICS]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_BASICS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_BASICS,
  Action.REQUEST_FETCH_GROUP_BASICS,
  Action.REQUEST_FETCH_COMPANY_BASICS,
);

const fetchBasics = (subject, node, request, finish) => (id, timeframeId) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.basics;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(request());
    return getJson(`/${subject}/${id}/basics.json`, status)({ tfid: timeframeId })
      .then(json => dispatch(finish(node, json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finish(new Error(message)));
      });
  };

export const fetchUserBasics = (id, timeframeId) =>
  fetchBasics('users', 'user', requestFetchUserBasics, finishFetchUserBasics)(id, timeframeId);

export const fetchGroupBasics = (id, timeframeId) =>
  fetchBasics('groups', 'group', requestFetchGroupBasics, finishFetchGroupBasics)(id, timeframeId);

export const fetchCompanyBasics = (id, timeframeId) =>
  fetchBasics('companies', 'company', requestFetchCompanyBasics, finishFetchCompanyBasics)(id, timeframeId);
