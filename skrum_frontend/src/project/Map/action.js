import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_OKRS: 'REQUEST_FETCH_USER_OKRS',
  REQUEST_FETCH_GROUP_OKRS: 'REQUEST_FETCH_GROUP_OKRS',
  REQUEST_FETCH_COMPANY_OKRS: 'REQUEST_FETCH_COMPANY_OKRS',
  FINISH_FETCH_USER_OKRS: 'FINISH_FETCH_USER_OKRS',
  FINISH_FETCH_GROUP_OKRS: 'FINISH_FETCH_GROUP_OKRS',
  FINISH_FETCH_COMPANY_OKRS: 'FINISH_FETCH_COMPANY_OKRS',
};

const {
  requestFetchUserOkrs,
  requestFetchGroupOkrs,
  requestFetchCompanyOkrs,
  finishFetchUserOkrs,
  finishFetchGroupOkrs,
  finishFetchCompanyOkrs,
} = createActions({
  [Action.FINISH_FETCH_USER_OKRS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_OKRS]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_OKRS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_OKRS,
  Action.REQUEST_FETCH_GROUP_OKRS,
  Action.REQUEST_FETCH_COMPANY_OKRS,
);

const fetchMap = (subject, node, request, finish) => (id, timeframeId) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.map;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(request());
    return getJson(`/${subject}/${id}/okrs.json`, status)({ tfid: timeframeId })
      .then(json => dispatch(finish(node, json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finish(new Error(message)));
      });
  };

export const fetchUserOkrs = (id, timeframeId) =>
  fetchMap('users', 'user', requestFetchUserOkrs, finishFetchUserOkrs)(id, timeframeId);

export const fetchGroupOkrs = (id, timeframeId) =>
  fetchMap('groups', 'group', requestFetchGroupOkrs, finishFetchGroupOkrs)(id, timeframeId);

export const fetchCompanyOkrs = (id, timeframeId) =>
  fetchMap('companies', 'company', requestFetchCompanyOkrs, finishFetchCompanyOkrs)(id, timeframeId);
