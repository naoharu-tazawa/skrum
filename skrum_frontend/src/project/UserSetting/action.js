import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, postJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY_ROLES: 'REQUEST_FETCH_COMPANY_ROLES',
  FINISH_FETCH_COMPANY_ROLES: 'FINISH_FETCH_COMPANY_ROLES',
  REQUEST_POST_INVITE: 'REQUEST_POST_INVITE',
  FINISH_POST_INVITE: 'FINISH_POST_INVITE',
};

const {
  requestFetchCompanyRoles,
  finishFetchCompanyRoles,
  requestPostInvite,
  finishPostInvite,
} = createActions({
  [Action.FINISH_FETCH_COMPANY_ROLES]: keyValueIdentity,
  [Action.FINISH_POST_INVITE]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY_ROLES,
  Action.REQUEST_POST_INVITE,
);

export const fetchCompanyRoles = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompanyRoles());
    return getJson(`/companies/${companyId}/roles.json`, state)()
      .then(json => dispatch(finishFetchCompanyRoles('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompanyRoles(new Error(message))));
  };

export const postInvite = (emailAddress, roleAssignmentId) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isPosting) return Promise.resolve();
    dispatch(requestPostInvite());
    return postJson('/invite.json', state)(null, { emailAddress, roleAssignmentId })
      .then(json => dispatch(finishPostInvite('data', json)))
      .catch(({ message }) => dispatch(finishPostInvite(new Error(message))));
  };
