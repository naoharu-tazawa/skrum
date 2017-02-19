import { createActions } from 'redux-actions';
import { browserHistory } from 'react-router';
import { keyValueIdentity } from '../util/ActionUtil';

export const Action = {
  REQUEST_LOGIN: 'REQUEST_LOGIN',
  FINISH_LOGIN: 'FINISH_LOGIN',
  REQUEST_LOGOUT: 'REQUEST_LOGOUT',
  FORCE_LOGOUT: 'FORCE_LOGOUT',
};

const {
  requestLogin,
  finishLogin,
  requestLogout,
  forceLogout,
} = createActions({
  [Action.FINISH_LOGIN]: keyValueIdentity,
  [Action.FORCE_LOGOUT]: keyValueIdentity,
},
  Action.REQUEST_LOGIN,
  Action.REQUEST_LOGOUT,
);

// mocking
function doPostLogin() {
  return new Promise((resolve) => {
    const user = {
      secretKey: 'hfaiugavaaaghaliuhgbvauhega',
      username: 'test',
    };
    setTimeout(() => resolve({ user }), 1000);
  });
}

export const logout = requestLogout;

export function logoutForce(message, dispatch) {
  return new Promise(resolve => resolve(dispatch(forceLogout(message))))
    .then(() => dispatch(browserHistory.push('/login')));
}

export function postLogin(request = { username: null, password: null }) {
  return (dispatch, getStatus) => {
    if (getStatus().isFetching) {
      return Promise.resolve();
    }

    dispatch(requestLogin());
    return doPostLogin(request)
      .then(resp => dispatch(finishLogin('data', resp)))
      .catch(err => dispatch(finishLogin(new Error(err.message))));
  };
}
