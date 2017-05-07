import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_GROUPS: 'REQUEST_FETCH_USER_GROUPS',
  FINISH_FETCH_USER_GROUPS: 'FINISH_FETCH_USER_GROUPS',
};

const {
  requestFetchUserGroups,
  finishFetchUserGroups,
} = createActions({
  [Action.FINISH_FETCH_USER_GROUPS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_GROUPS,
);

export function fetchUserGroups(userId = 1, tfid = 1) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.user;
    if (isFetching) {
      return Promise.resolve();
    }

    dispatch(requestFetchUserGroups());
    return getJson(`/users/${userId}/groups.json?tfid=${tfid}`, status)()
      .then(json => dispatch(finishFetchUserGroups('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserGroups(new Error(message)));
      });
  };
}
