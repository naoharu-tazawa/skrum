import { createActions } from 'redux-actions';
import { browserHistory } from 'react-router';
import { postJson } from '../util/ApiUtil';
import { keyValueIdentity } from '../util/ActionUtil';

export const Action = {
  REQUEST_LOGIN: 'REQUEST_LOGIN',
  FINISH_LOGIN: 'FINISH_LOGIN',
  REQUEST_LOGOUT: 'REQUEST_LOGOUT',
  REQUEST_PREREGISTER: 'REQUEST_PREREGISTER',
  FINISH_PREREGISTER: 'FINISH_PREREGISTER',
  REQUEST_USER_SIGN_UP: 'REQUEST_USER_SIGN_UP',
  FINISH_USER_SIGN_UP: 'FINISH_USER_SIGN_UP',
  REQUEST_USER_JOIN: 'REQUEST_USER_JOIN',
  FINISH_USER_JOIN: 'FINISH_USER_JOIN',
};

const {
  requestLogin,
  finishLogin,
  requestLogout,
  requestPreregister,
  finishPreregister,
  requestUserSignUp,
  finishUserSignUp,
  requestUserJoin,
  finishUserJoin,
} = createActions({
  [Action.FINISH_LOGIN]: keyValueIdentity,
  [Action.FINISH_PREREGISTER]: keyValueIdentity,
  [Action.FINISH_USER_SIGN_UP]: keyValueIdentity,
  [Action.FINISH_USER_JOIN]: keyValueIdentity,
},
  Action.REQUEST_LOGIN,
  Action.REQUEST_LOGOUT,
  Action.REQUEST_PREREGISTER,
  Action.REQUEST_USER_SIGN_UP,
  Action.REQUEST_USER_JOIN,
);

export const logout = requestLogout;

export const forceLogout = () =>
  (dispatch) => {
    dispatch(forceLogout());
    browserHistory.push('/login');
    return requestLogin();
  };

export const startLogin = (emailAddress, password) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.isPosting) return Promise.resolve();
    dispatch(requestLogin());
    return postJson('/login.json', state)(null, { emailAddress, password })
      .then(resp => dispatch(finishLogin('data', resp)))
      .catch(err => dispatch(finishLogin(new Error(err.message))));
  };

export const preregister = (emailAddress, subdomain) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.isPreregistering) return Promise.resolve();
    dispatch(requestPreregister());
    return postJson('/preregister.json', state)(null, { emailAddress, subdomain })
      .then(() => dispatch(finishPreregister('data', { emailAddress, subdomain })))
      .catch(err => dispatch(finishPreregister(new Error(err.message))));
  };

export const userSignUp = (password, urltoken) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.isPosting) return Promise.resolve();
    dispatch(requestUserSignUp());
    return postJson('/signup.json', state)(null, { password, urltoken })
      .then(json => dispatch(finishUserSignUp('data', json)))
      .catch(err => dispatch(finishUserSignUp(new Error(err.message))));
  };

export const userJoin = (password, urltoken) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.isPosting) return Promise.resolve();
    dispatch(requestUserJoin());
    return postJson('/join.json', state)(null, { password, urltoken })
      .then(json => dispatch(finishUserJoin('data', json)))
      .catch(err => dispatch(finishUserJoin(new Error(err.message))));
  };
