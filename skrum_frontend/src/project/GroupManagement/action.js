import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson, postJson, deleteJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_GROUPS: 'REQUEST_FETCH_USER_GROUPS',
  FINISH_FETCH_USER_GROUPS: 'FINISH_FETCH_USER_GROUPS',
  REQUEST_FETCH_GROUP_MEMBERS: 'REQUEST_FETCH_GROUP_MEMBERS',
  FINISH_FETCH_GROUP_MEMBERS: 'FINISH_FETCH_GROUP_MEMBERS',
  REQUEST_PUT_USER: 'REQUEST_PUT_USER',
  FINISH_PUT_USER: 'FINISH_PUT_USER',
  REQUEST_PUT_GROUP: 'REQUEST_PUT_GROUP',
  FINISH_PUT_GROUP: 'FINISH_PUT_GROUP',
  REQUEST_POST_USER_IMAGE: 'REQUEST_POST_USER_IMAGE',
  FINISH_POST_USER_IMAGE: 'FINISH_POST_USER_IMAGE',
  REQUEST_POST_GROUP_IMAGE: 'REQUEST_POST_GROUP_IMAGE',
  FINISH_POST_GROUP_IMAGE: 'FINISH_POST_GROUP_IMAGE',
  REQUEST_CHANGE_GROUP_LEADER: 'REQUEST_CHANGE_GROUP_LEADER',
  FINISH_CHANGE_GROUP_LEADER: 'FINISH_CHANGE_GROUP_LEADER',
  REQUEST_ADD_GROUP_MEMBER: 'REQUEST_ADD_GROUP_MEMBER',
  FINISH_ADD_GROUP_MEMBER: 'FINISH_ADD_GROUP_MEMBER',
  REQUEST_DELETE_GROUP_MEMBER: 'REQUEST_DELETE_GROUP_MEMBER',
  FINISH_DELETE_GROUP_MEMBER: 'FINISH_DELETE_GROUP_MEMBER',
  REQUEST_JOIN_GROUP: 'REQUEST_JOIN_GROUP',
  FINISH_JOIN_GROUP: 'FINISH_JOIN_GROUP',
  REQUEST_LEAVE_GROUP: 'REQUEST_LEAVE_GROUP',
  FINISH_LEAVE_GROUP: 'FINISH_LEAVE_GROUP',
  REQUEST_ADD_GROUP_PATH: 'REQUEST_ADD_GROUP_PATH',
  FINISH_ADD_GROUP_PATH: 'FINISH_ADD_GROUP_PATH',
  REQUEST_DELETE_GROUP_PATH: 'REQUEST_DELETE_GROUP_PATH',
  FINISH_DELETE_GROUP_PATH: 'FINISH_DELETE_GROUP_PATH',
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
  requestPostUserImage,
  finishPostUserImage,
  requestPostGroupImage,
  finishPostGroupImage,
  requestChangeGroupLeader,
  finishChangeGroupLeader,
  requestAddGroupMember,
  finishAddGroupMember,
  requestDeleteGroupMember,
  finishDeleteGroupMember,
  requestJoinGroup,
  finishJoinGroup,
  requestLeaveGroup,
  finishLeaveGroup,
  requestAddGroupPath,
  finishAddGroupPath,
  requestDeleteGroupPath,
  finishDeleteGroupPath,
} = createActions({
  [Action.FINISH_FETCH_USER_GROUPS]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_MEMBERS]: keyValueIdentity,
  [Action.FINISH_PUT_USER]: keyValueIdentity,
  [Action.FINISH_PUT_GROUP]: keyValueIdentity,
  [Action.FINISH_POST_USER_IMAGE]: keyValueIdentity,
  [Action.FINISH_POST_GROUP_IMAGE]: keyValueIdentity,
  [Action.FINISH_CHANGE_GROUP_LEADER]: keyValueIdentity,
  [Action.FINISH_ADD_GROUP_MEMBER]: keyValueIdentity,
  [Action.FINISH_DELETE_GROUP_MEMBER]: keyValueIdentity,
  [Action.FINISH_JOIN_GROUP]: keyValueIdentity,
  [Action.FINISH_LEAVE_GROUP]: keyValueIdentity,
  [Action.FINISH_ADD_GROUP_PATH]: keyValueIdentity,
  [Action.FINISH_DELETE_GROUP_PATH]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_GROUPS,
  Action.REQUEST_FETCH_GROUP_MEMBERS,
  Action.REQUEST_PUT_USER,
  Action.REQUEST_PUT_GROUP,
  Action.REQUEST_POST_USER_IMAGE,
  Action.REQUEST_POST_GROUP_IMAGE,
  Action.REQUEST_CHANGE_GROUP_LEADER,
  Action.REQUEST_ADD_GROUP_MEMBER,
  Action.REQUEST_DELETE_GROUP_MEMBER,
  Action.REQUEST_JOIN_GROUP,
  Action.REQUEST_LEAVE_GROUP,
  Action.REQUEST_ADD_GROUP_PATH,
  Action.REQUEST_DELETE_GROUP_PATH,
);

export const fetchUserGroups = (userId, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isFetching) return Promise.resolve();
    dispatch(requestFetchUserGroups());
    return getJson(`/users/${userId}/groups.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finishFetchUserGroups('data', json)))
      .catch(({ message }) => dispatch(finishFetchUserGroups(new Error(message))));
  };

export const fetchGroupMembers = (groupId, timeframeId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isFetching) return Promise.resolve();
    dispatch(requestFetchGroupMembers());
    return getJson(`/groups/${groupId}/members.json`, state)({ tfid: timeframeId })
      .then(json => dispatch(finishFetchGroupMembers('data', json)))
      .catch(({ message }) => dispatch(finishFetchGroupMembers(new Error(message))));
  };

export const putUser = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isPutting) return Promise.resolve();
    dispatch(requestPutUser());
    return putJson(`/users/${id}.json`, state)(null, data)
      .then(() => dispatch(finishPutUser('data', { id, ...data })))
      .catch(({ message }) => dispatch(finishPutUser(new Error(message))));
  };

export const putGroup = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isPutting) return Promise.resolve();
    dispatch(requestPutGroup());
    return putJson(`/groups/${id}.json`, state)(null, data)
      .then(() => dispatch(finishPutGroup('data', { id, ...data })))
      .catch(({ message }) => dispatch(finishPutGroup(new Error(message))));
  };

export const postUserImage = (id, image, mimeType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isPostingImage) return Promise.resolve();
    dispatch(requestPostUserImage());
    return postJson(`/users/${id}/images.json`, state)(null, { image, mimeType })
      .then(json => dispatch(finishPostUserImage('data', json)))
      .catch(({ message }) => dispatch(finishPostUserImage(new Error(message))));
  };

export const postGroupImage = (id, image, mimeType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isPostingImage) return Promise.resolve();
    dispatch(requestPostGroupImage());
    return postJson(`/groups/${id}/images.json`, state)(null, { image, mimeType })
      .then(json => dispatch(finishPostGroupImage('data', json)))
      .catch(({ message }) => dispatch(finishPostGroupImage(new Error(message))));
  };

export const changeGroupLeader = (groupId, userId, userName) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isChangingLeader) return Promise.resolve();
    dispatch(requestChangeGroupLeader());
    return putJson(`/groups/${groupId}/leaders/${userId}.json`, state)()
      .then(() => dispatch(finishChangeGroupLeader('data', { groupId, userId, userName })))
      .catch(({ message }) => dispatch(finishChangeGroupLeader(new Error(message))));
  };

export const addGroupMember = (timeframeId, groupId, userId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isAddingGroupMember) return Promise.resolve();
    dispatch(requestAddGroupMember());
    return postJson(`/groups/${groupId}/members.json`, state)({ tfid: timeframeId }, { userId })
      .then(json => dispatch(finishAddGroupMember('data', json)))
      .catch(({ message }) => dispatch(finishAddGroupMember(new Error(message))));
  };

export const deleteGroupMember = (groupId, userId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isDeletingGroupMember) return Promise.resolve();
    dispatch(requestDeleteGroupMember());
    return deleteJson(`/groups/${groupId}/members/${userId}.json`, state)()
      .then(() => dispatch(finishDeleteGroupMember('data', { groupId, userId })))
      .catch(({ message }) => dispatch(finishDeleteGroupMember(new Error(message))));
  };

export const joinGroup = (timeframeId, userId, groupId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isJoiningGroup) return Promise.resolve();
    dispatch(requestJoinGroup());
    return postJson(`/groups/${groupId}/members.json`, state)({ tfid: timeframeId }, { userId })
      .then(json => dispatch(finishJoinGroup('data', json)))
      .catch(({ message }) => dispatch(finishJoinGroup(new Error(message))));
  };

export const leaveGroup = (userId, groupId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isLeavingGroup) return Promise.resolve();
    dispatch(requestLeaveGroup());
    return deleteJson(`/groups/${groupId}/members/${userId}.json`, state)()
      .then(() => dispatch(finishLeaveGroup('data', { userId, groupId })))
      .catch(({ message }) => dispatch(finishLeaveGroup(new Error(message))));
  };

export const addGroupPath = (groupId, groupPathId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isAddingGroupPath) return Promise.resolve();
    dispatch(requestAddGroupPath());
    return postJson(`/groups/${groupId}/paths.json`, state)(null, { groupPathId })
      .then(json => dispatch(finishAddGroupPath('data', json)))
      .catch(({ message }) => dispatch(finishAddGroupPath(new Error(message))));
  };

export const deleteGroupPath = (groupId, groupPathId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupManagement.isDeletingGroupPath) return Promise.resolve();
    dispatch(requestDeleteGroupPath());
    return deleteJson(`/groups/${groupId}/paths/${groupPathId}.json`, state)()
      .then(() => dispatch(finishDeleteGroupPath('data', { groupId, groupPathId })))
      .catch(({ message }) => dispatch(finishDeleteGroupPath(new Error(message))));
  };
