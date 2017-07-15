import { createActions } from 'redux-actions';
import { browserHistory } from 'react-router';
import { postJson } from '../util/ApiUtil';
import { keyValueIdentity } from '../util/ActionUtil';

export const Action = {
  REQUEST_LOGIN: 'REQUEST_LOGIN',
  FINISH_LOGIN: 'FINISH_LOGIN',
  REQUEST_LOGOUT: 'REQUEST_LOGOUT',
};

const {
  requestLogin,
  finishLogin,
  requestLogout,
} = createActions({
  [Action.FINISH_LOGIN]: keyValueIdentity,
},
  Action.REQUEST_LOGIN,
  Action.REQUEST_LOGOUT,
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
