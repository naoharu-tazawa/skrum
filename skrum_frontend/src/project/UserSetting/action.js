import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson, putJson, deleteJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY_ROLES: 'REQUEST_FETCH_COMPANY_ROLES',
  FINISH_FETCH_COMPANY_ROLES: 'FINISH_FETCH_COMPANY_ROLES',
  REQUEST_FETCH_USERS: 'REQUEST_FETCH_USERS',
  FINISH_FETCH_USERS: 'FINISH_FETCH_USERS',
  REQUEST_POST_INVITE: 'REQUEST_POST_INVITE',
  FINISH_POST_INVITE: 'FINISH_POST_INVITE',
  REQUEST_RESET_PASSWORD: 'REQUEST_RESET_PASSWORD',
  FINISH_RESET_PASSWORD: 'FINISH_RESET_PASSWORD',
  REQUEST_ASSIGN_ROLE: 'REQUEST_ASSIGN_ROLE',
  FINISH_ASSIGN_ROLE: 'FINISH_ASSIGN_ROLE',
  REQUEST_DELETE_USER: 'REQUEST_DELETE_USER',
  FINISH_DELETE_USER: 'FINISH_DELETE_USER',
};

const {
  requestFetchCompanyRoles,
  finishFetchCompanyRoles,
  requestFetchUsers,
  finishFetchUsers,
  requestPostInvite,
  finishPostInvite,
  requestResetPassword,
  finishResetPassword,
  requestAssignRole,
  finishAssignRole,
  requestDeleteUser,
  finishDeleteUser,
} = createActions({
  [Action.FINISH_FETCH_COMPANY_ROLES]: keyValueIdentity,
  [Action.FINISH_FETCH_USERS]: keyValueIdentity,
  [Action.FINISH_POST_INVITE]: keyValueIdentity,
  [Action.FINISH_RESET_PASSWORD]: keyValueIdentity,
  [Action.FINISH_ASSIGN_ROLE]: keyValueIdentity,
  [Action.FINISH_DELETE_USER]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY_ROLES,
  Action.REQUEST_FETCH_USERS,
  Action.REQUEST_POST_INVITE,
  Action.REQUEST_RESET_PASSWORD,
  Action.REQUEST_ASSIGN_ROLE,
  Action.REQUEST_DELETE_USER,
);

export const fetchCompanyRoles = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isFetchingRoles) return Promise.resolve();
    dispatch(requestFetchCompanyRoles());
    return getJson(`/companies/${companyId}/roles.json`, state)()
      .then(json => dispatch(finishFetchCompanyRoles('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompanyRoles(new Error(message))));
  };

export const fetchUsers = (keyword, pageNo) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isFetchingUsers) return Promise.resolve();
    dispatch(requestFetchUsers());
    return getJson('/users/pagesearch.json', state)({ q: keyword, p: pageNo })
      .then(json => dispatch(finishFetchUsers('data', json)))
      .catch(({ message }) => dispatch(finishFetchUsers(new Error(message))));
  };

export const postInvite = (emailAddress, roleAssignmentId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isPostingInvite) return Promise.resolve();
    dispatch(requestPostInvite());
    return postJson('/invite.json', state)(null, { emailAddress, roleAssignmentId })
      .then(json => dispatch(finishPostInvite('data', json)))
      .catch(({ message }) => dispatch(finishPostInvite(new Error(message))));
  };

export const resetPassword = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isResettingPassword) return Promise.resolve();
    dispatch(requestResetPassword());
    return postJson(`/users/${id}/resetpassword.json`, state)()
      .then(json => dispatch(finishResetPassword('data', json)))
      .catch(({ message }) => dispatch(finishResetPassword(new Error(message))));
  };

export const assignRole = (id, roleAssignmentId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isAssigningRole) return Promise.resolve();
    dispatch(requestAssignRole());
    return putJson(`/users/${id}/roles/${roleAssignmentId}.json`, state)()
      .then(json => dispatch(finishAssignRole('data', { id, data: json })))
      .catch(({ message }) => dispatch(finishAssignRole(new Error(message))));
  };

export const deleteUser = id =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isDeletingUser) return Promise.resolve();
    dispatch(requestDeleteUser());
    return deleteJson(`/users/${id}.json`, state)()
      .then(() => dispatch(finishDeleteUser('data', { id })))
      .catch(({ message }) => dispatch(finishDeleteUser(new Error(message))));
  };
