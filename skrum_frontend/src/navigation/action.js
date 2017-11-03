import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../util/ActionUtil';
import { getJson, postJson } from '../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_TOP: 'REQUEST_FETCH_USER_TOP',
  FINISH_FETCH_USER_TOP: 'FINISH_FETCH_USER_TOP',
  REQUIRE_FETCH_USER_TOP: 'REQUIRE_FETCH_USER_TOP',
  REQUEST_SETUP_COMPANY: 'REQUEST_SETUP_COMPANY',
  FINISH_SETUP_COMPANY: 'FINISH_SETUP_COMPANY',
  REQUEST_SETUP_USER: 'REQUEST_SETUP_USER',
  FINISH_SETUP_USER: 'FINISH_SETUP_USER',
  ADD_GROUP: 'ADD_GROUP',
  REMOVE_GROUP: 'REMOVE_GROUP',
  UPDATE_ONE_ON_ONE: 'UPDATE_ONE_ON_ONE',
};

const {
  requestFetchUserTop,
  finishFetchUserTop,
  requireFetchUserTop,
  requestSetupCompany,
  finishSetupCompany,
  requestSetupUser,
  finishSetupUser,
  addGroup,
  removeGroup,
  updateOneOnOne,
} = createActions({
  [Action.FINISH_FETCH_USER_TOP]: keyValueIdentity,
  [Action.FINISH_SETUP_COMPANY]: keyValueIdentity,
  [Action.FINISH_SETUP_USER]: keyValueIdentity,
  [Action.ADD_GROUP]: keyValueIdentity,
  [Action.REMOVE_GROUP]: keyValueIdentity,
  [Action.UPDATE_ONE_ON_ONE]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_TOP,
  Action.REQUIRE_FETCH_USER_TOP,
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

export const touchUserTop = () =>
  dispatch => dispatch(requireFetchUserTop());

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

export const syncJoinGroup = ({ payload, error }) =>
  dispatch => (!error ?
    dispatch(addGroup('data', payload.data)) :
    dispatch(addGroup(new Error(payload.message))));

export const syncLeaveGroup = ({ payload, error }) =>
  dispatch => (!error ?
    dispatch(removeGroup('data', payload.data)) :
    dispatch(removeGroup(new Error(payload.message))));

export const syncOneOnOne = ({ payload, error }) =>
  dispatch => (!error ?
    dispatch(updateOneOnOne('data', payload.data)) :
    dispatch(updateOneOnOne(new Error(payload.message))));
