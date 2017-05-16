import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_GROUPS: 'REQUEST_FETCH_USER_GROUPS',
  REQUEST_FETCH_GROUP_MEMBERS: 'REQUEST_FETCH_GROUP_MEMBERS',
  FINISH_FETCH_USER_GROUPS: 'FINISH_FETCH_USER_GROUPS',
  FINISH_FETCH_GROUP_MEMBERS: 'FINISH_FETCH_GROUP_MEMBERS',
};

const {
  requestFetchUserGroups,
  requestFetchGroupMembers,
  finishFetchUserGroups,
  finishFetchGroupMembers,
} = createActions({
  [Action.FINISH_FETCH_USER_GROUPS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_MEMBERS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_GROUPS,
  Action.REQUEST_FETCH_GROUP_MEMBERS,
);

export function fetchUserGroups(userId, timeframeId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.groupManagement;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchUserGroups());
    return getJson(`/users/${userId}/groups.json`, status)({ tfid: timeframeId })
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
    dispatch(requestFetchGroupMembers());
    return getJson(`/groups/${groupId}/members.json`, status)()
      .then(json => dispatch(finishFetchGroupMembers('group', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchGroupMembers(new Error(message)));
      });
  };
}
