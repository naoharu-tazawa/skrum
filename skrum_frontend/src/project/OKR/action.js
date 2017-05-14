import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_BASICS: 'REQUEST_FETCH_USER_BASICS',
  FINISH_FETCH_USER_BASICS: 'FINISH_FETCH_USER_BASICS',
};

const {
  requestFetchUserBasics,
  finishFetchUserBasics,
} = createActions({
  [Action.FINISH_FETCH_USER_BASICS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_BASICS,
);

export function fetchUserBasics(userId, timeframeId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.basics;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchUserBasics());
    return getJson(`/users/${userId}/basics.json?tfid=${timeframeId}`, status)()
      .then(json => dispatch(finishFetchUserBasics('user', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserBasics(new Error(message)));
      });
  };
}

export function fetchGroupBasics(groupId, timeframeId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.basics;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchUserBasics());
    return getJson(`/groups/${groupId}/basics.json?tfid=${timeframeId}`, status)()
      .then(json => dispatch(finishFetchUserBasics('group', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserBasics(new Error(message)));
      });
  };
}

export function fetchCompanyBasics(companyId, timeframeId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.basics;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchUserBasics());
    return getJson(`/companies/${companyId}/basics.json?tfid=${timeframeId}`, status)()
      .then(json => dispatch(finishFetchUserBasics('company', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserBasics(new Error(message)));
      });
  };
}
