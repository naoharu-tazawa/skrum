import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_GROUPS: 'REQUEST_FETCH_USER_GROUPS',
  FINISH_FETCH_USER_GROUPS: 'FINISH_FETCH_USER_GROUPS',
  REQUEST_FETCH_GROUP_MEMBERS: 'REQUEST_FETCH_GROUP_MEMBERS',
  FINISH_FETCH_GROUP_MEMBERS: 'FINISH_FETCH_GROUP_MEMBERS',
  REQUEST_PUT_USER: 'REQUEST_PUT_USER',
  FINISH_PUT_USER: 'FINISH_PUT_USER',
  REQUEST_PUT_GROUP: 'REQUEST_PUT_GROUP',
  FINISH_PUT_GROUP: 'FINISH_PUT_GROUP',
};

const {
  requestFetchUserGroups,
  finishFetchUserGroups,
  requestFetchGroupMembers,
  finishFetchGroupMembers,
  requestPutUser,
  finishPutUser,
  requestPutGroup,
  finishPutGroup,
} = createActions({
  [Action.FINISH_FETCH_USER_GROUPS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_MEMBERS]: keyValueIdentity,
  [Action.FINISH_PUT_USER]: keyValueIdentity,
  [Action.REQUEST_PUT_USER]: keyValueIdentity,
  [Action.FINISH_PUT_GROUP]: keyValueIdentity,
  [Action.REQUEST_PUT_GROUP]: keyValueIdentity,
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

export function fetchGroupMembers(groupId, timeframeId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.groupManagement;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchGroupMembers());
    return getJson(`/groups/${groupId}/members.json`, status)({ tfid: timeframeId })
      .then(json => dispatch(finishFetchGroupMembers('group', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchGroupMembers(new Error(message)));
      });
  };
}

export const putUser = (id, data) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isPutting } = status.groupManagement;
    if (isPutting) {
      return Promise.resolve();
    }
    dispatch(requestPutUser('data', { id: Number(id), ...data }));
    return putJson(`/users/${id}.json`, status)(null, data)
      .then(json => dispatch(finishPutUser('data', json)))
      .catch(({ message }) => dispatch(finishPutUser(new Error(message))));
  };

export const putGroup = (id, data) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isPutting } = status.groupManagement;
    if (isPutting) {
      return Promise.resolve();
    }
    dispatch(requestPutGroup('data', { id: Number(id), ...data }));
    return putJson(`/groups/${id}.json`, status)(null, data)
      .then(json => dispatch(finishPutGroup('data', json)))
      .catch(({ message }) => dispatch(finishPutGroup(new Error(message))));
  };
