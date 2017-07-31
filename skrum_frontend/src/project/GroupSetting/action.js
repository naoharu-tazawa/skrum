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

export const fetchCompanyRoles = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.userSetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompanyRoles());
    return getJson(`/companies/${companyId}/roles.json`, state)()
      .then(json => dispatch(finishFetchCompanyRoles('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompanyRoles(new Error(message))));
  };
