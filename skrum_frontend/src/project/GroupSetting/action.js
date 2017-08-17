import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson, deleteJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_GROUPS: 'REQUEST_FETCH_GROUPS',
  FINISH_FETCH_GROUPS: 'FINISH_FETCH_GROUPS',
  REQUEST_CREATE_GROUP: 'REQUEST_CREATE_GROUP',
  FINISH_CREATE_GROUP: 'FINISH_CREATE_GROUP',
  REQUEST_DELETE_GROUP: 'REQUEST_DELETE_GROUP',
  FINISH_DELETE_GROUP: 'FINISH_DELETE_GROUP',
};

const {
  requestFetchGroups,
  finishFetchGroups,
  requestCreateGroup,
  finishCreateGroup,
  requestDeleteGroup,
  finishDeleteGroup,
} = createActions({
  [Action.FINISH_FETCH_GROUPS]: keyValueIdentity,
  [Action.FINISH_CREATE_GROUP]: keyValueIdentity,
  [Action.FINISH_DELETE_GROUP]: keyValueIdentity,
},
  Action.REQUEST_FETCH_GROUPS,
  Action.REQUEST_CREATE_GROUP,
  Action.REQUEST_DELETE_GROUP,
);

export const fetchGroups = (keyword, pageNo) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isFetchingGroups) return Promise.resolve();
    dispatch(requestFetchGroups());
    return getJson('/groups/pagesearch.json', state)({ q: keyword, p: pageNo })
      .then(json => dispatch(finishFetchGroups('data', json)))
      .catch(({ message }) => dispatch(finishFetchGroups(new Error(message))));
  };

export const createGroup = data =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupSetting.isPostingGroup) return Promise.resolve();
    dispatch(requestCreateGroup());
    return postJson('/groups.json', state)(null, data)
      .then(json => dispatch(finishCreateGroup('data', { data: json })))
      .catch(({ message }) => dispatch(finishCreateGroup(new Error(message))));
  };

export const deleteGroup = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.groupSetting.isDeletingGroup) return Promise.resolve();
    dispatch(requestDeleteGroup());
    return deleteJson(`/groups/${id}.json`, state)()
      .then(() => dispatch(finishDeleteGroup('data', { id })))
      .catch(({ message }) => dispatch(finishDeleteGroup(new Error(message))));
  };
