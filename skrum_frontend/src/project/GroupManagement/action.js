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
  REQUEST_CHANGE_GROUP_LEADER: 'REQUEST_CHANGE_GROUP_LEADER',
  FINISH_CHANGE_GROUP_LEADER: 'FINISH_CHANGE_GROUP_LEADER',
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
  requestChangeGroupLeader,
  finishChangeGroupLeader,
} = createActions({
  [Action.FINISH_FETCH_USER_GROUPS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_MEMBERS]: keyValueIdentity,
  [Action.FINISH_PUT_USER]: keyValueIdentity,
  [Action.REQUEST_PUT_USER]: keyValueIdentity,
  [Action.FINISH_PUT_GROUP]: keyValueIdentity,
  [Action.REQUEST_PUT_GROUP]: keyValueIdentity,
  [Action.FINISH_CHANGE_GROUP_LEADER]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_GROUPS,
  Action.REQUEST_FETCH_GROUP_MEMBERS,
  Action.REQUEST_CHANGE_GROUP_LEADER,
);

export const fetchUserGroups = (userId, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isFetching) return Promise.resolve();
    dispatch(requestFetchUserGroups());
    return getJson(`/users/${userId}/groups.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finishFetchUserGroups('user', json)))
      .catch(({ message }) => dispatch(finishFetchUserGroups(new Error(message))));
  };

export const fetchGroupMembers = (groupId, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isFetching) return Promise.resolve();
    dispatch(requestFetchGroupMembers());
    return getJson(`/groups/${groupId}/members.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finishFetchGroupMembers('group', json)))
      .catch(({ message }) => dispatch(finishFetchGroupMembers(new Error(message))));
  };

export const putUser = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isPutting) return Promise.resolve();
    dispatch(requestPutUser('data', { id: Number(id), ...data }));
    return putJson(`/users/${id}.json`, state)(null, data)
      .then(json => dispatch(finishPutUser('data', json)))
      .catch(({ message }) => dispatch(finishPutUser(new Error(message))));
  };

export const putGroup = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isPutting) return Promise.resolve();
    dispatch(requestPutGroup('data', { id: Number(id), ...data }));
    return putJson(`/groups/${id}.json`, state)(null, data)
      .then(json => dispatch(finishPutGroup('data', json)))
      .catch(({ message }) => dispatch(finishPutGroup(new Error(message))));
  };

export const changeGroupLeader = (groupId, userId, userName) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isChangingLeader) return Promise.resolve();
    dispatch(requestChangeGroupLeader());
    return putJson(`/groups/${groupId}/leaders/${userId}.json`, state)()
      .then(() => dispatch(finishChangeGroupLeader('changeGroupLeader', { groupId, userId, userName })))
      .catch(({ message }) => dispatch(finishChangeGroupLeader(new Error(message))));
  };