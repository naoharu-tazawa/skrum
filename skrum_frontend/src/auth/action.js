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
};

const {
  requestLogin,
  finishLogin,
  requestLogout,
  requestPreregister,
  finishPreregister,
} = createActions({
  [Action.FINISH_LOGIN]: keyValueIdentity,
  [Action.FINISH_PREREGISTER]: keyValueIdentity,
},
  Action.REQUEST_LOGIN,
  Action.REQUEST_LOGOUT,
  Action.REQUEST_PREREGISTER,
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
    if (state.isFetching) return Promise.resolve();
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
