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

export function fetchUserGroups(userId, timeframeId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.groupManagement;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchUserGroups());
    return getJson(`/users/${userId}/groups.json?tfid=${timeframeId}`, status)()
      .then(json => dispatch(finishFetchUserGroups('user', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserGroups(new Error(message)));
      });
  };
}

export function fetchGroupMembers(groupId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.groupManagement;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchUserGroups());
    return getJson(`/groups/${groupId}/members.json`, status)()
      .then(json => dispatch(finishFetchUserGroups('group', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchUserGroups(new Error(message)));
      });
  };
}
