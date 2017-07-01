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

export function fetchCompanyRoles(companyId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    if (status.userSetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompanyRoles());
    return getJson(`/companies/${companyId}/roles.json`, status)()
      .then(json => dispatch(finishFetchCompanyRoles('roles', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchCompanyRoles(new Error(message)));
      });
  };
}

export function postInvite(emailAddress, roleAssignmentId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    if (status.userSetting.isPosting) return Promise.resolve();
    dispatch(requestPostInvite());
    return postJson('/invite.json', status)(null, { emailAddress, roleAssignmentId })
      .then(json => dispatch(finishPostInvite('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishPostInvite(new Error(message)));
      });
  };
}
