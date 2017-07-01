import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY_ROLES: 'REQUEST_FETCH_COMPANY_ROLES',
  FINISH_FETCH_COMPANY_ROLES: 'FINISH_FETCH_COMPANY_ROLES',
};

const {
  requestFetchCompanyRoles,
  finishFetchCompanyRoles,
} = createActions({
  [Action.FINISH_FETCH_COMPANY_ROLES]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY_ROLES,
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
