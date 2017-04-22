import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../util/ActionUtil';
import { getJson } from '../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_TOP: 'REQUEST_FETCH_USER_TOP',
  FINISH_FETCH_USER_TOP: 'FINISH_FETCH_USER_TOP',
};

const {
  requestFetchUserTop,
  finishFetchUserTop,
} = createActions({
  [Action.FINISH_FETCH_USER_TOP]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_TOP,
);

export function fetchUserTop(userId = 1) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.user;
    if (isFetching) {
      return Promise.resolve();
    }

    dispatch(requestFetchUserTop());
    return getJson(`/users/${userId}/top.json`, status)()
      .then(json => dispatch(finishFetchUserTop('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserTop(new Error(message)));
      });
  };
}
