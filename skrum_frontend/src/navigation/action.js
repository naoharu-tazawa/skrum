import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../util/ActionUtil';
import { getJson, postJson } from '../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_TOP: 'REQUEST_FETCH_USER_TOP',
  FINISH_FETCH_USER_TOP: 'FINISH_FETCH_USER_TOP',
  REQUEST_SETUP_COMPANY: 'REQUEST_SETUP_COMPANY',
  FINISH_SETUP_COMPANY: 'FINISH_SETUP_COMPANY',
  REQUEST_SETUP_USER: 'REQUEST_SETUP_USER',
  FINISH_SETUP_USER: 'FINISH_SETUP_USER',
};

const {
  requestFetchUserTop,
  finishFetchUserTop,
  requestSetupCompany,
  finishSetupCompany,
  requestSetupUser,
  finishSetupUser,
} = createActions({
  [Action.FINISH_FETCH_USER_TOP]: keyValueIdentity,
  [Action.FINISH_SETUP_COMPANY]: keyValueIdentity,
  [Action.FINISH_SETUP_USER]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_TOP,
  Action.REQUEST_SETUP_COMPANY,
  Action.REQUEST_SETUP_USER,
);

export const fetchUserTop = userId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.top.isFetching) return Promise.resolve();
    dispatch(requestFetchUserTop());
    return getJson(`/users/${userId}/top.json`, state)()
      .then(json => dispatch(finishFetchUserTop('data', json)))
      .catch(({ message }) => dispatch(finishFetchUserTop(new Error(message))));
  };

export const setupCompany = (companyId, setup) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.isPosting) return Promise.resolve();
    dispatch(requestSetupCompany());
    return postJson(`/companies/${companyId}/establish.json`, state)(null, setup)
      .then(json => dispatch(finishSetupCompany('data', json)))
      .catch(err => dispatch(finishSetupCompany(new Error(err.message))));
  };

export const setupUser = (userId, setup) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.isPosting) return Promise.resolve();
    dispatch(requestSetupUser());
    return postJson(`/users/${userId}/establish.json`, state)(null, setup)
      .then(json => dispatch(finishSetupUser('data', json)))
      .catch(err => dispatch(finishSetupUser(new Error(err.message))));
  };
