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

export const fetchUserTop = userId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.top.isFetching) return Promise.resolve();
    dispatch(requestFetchUserTop());
    return getJson(`/users/${userId}/top.json`, state)()
      .then(json => dispatch(finishFetchUserTop('data', json)))
      .catch(({ message }) => dispatch(finishFetchUserTop(new Error(message))));
  };
