import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY: 'REQUEST_FETCH_COMPANY',
  FINISH_FETCH_COMPANY: 'FINISH_FETCH_COMPANY',
  REQUEST_PUT_COMPANY: 'REQUEST_PUT_COMPANY',
  FINISH_PUT_COMPANY: 'FINISH_PUT_COMPANY',
};

const {
  requestFetchCompany,
  finishFetchCompany,
  requestPutCompany,
  finishPutCompany,
} = createActions({
  [Action.FINISH_FETCH_COMPANY]: keyValueIdentity,
  [Action.FINISH_PUT_COMPANY]: keyValueIdentity,
  [Action.REQUEST_PUT_COMPANY]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY,
);

export function fetchCompany(companyId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    if (status.companySetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompany());
    return getJson(`/companies/${companyId}.json`, status)()
      .then(json => dispatch(finishFetchCompany('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchCompany(new Error(message)));
      });
  };
}

export function putCompany(id, data) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    if (status.companySetting.isPutting) return Promise.resolve();
    dispatch(requestPutCompany('data', { id: Number(id), ...data }));
    return putJson(`/companies/${id}.json`, status)(null, data)
      .then(json => dispatch(finishPutCompany('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishPutCompany(new Error(message)));
      });
  };
}
