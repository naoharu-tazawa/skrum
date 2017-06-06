import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY: 'REQUEST_FETCH_COMPANY',
  REQUEST_PUT_COMPANY: 'REQUEST_PUT_COMPANY',
  FINISH_FETCH_COMPANY: 'FINISH_FETCH_COMPANY',
  FINISH_PUT_COMPANY: 'FINISH_PUT_COMPANY',
};

const {
  requestFetchCompany,
  requestPutCompany,
  finishFetchCompany,
  finishPutCompany,
} = createActions({
  [Action.FINISH_FETCH_COMPANY]: keyValueIdentity,
  [Action.FINISH_PUT_COMPANY]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY,
  Action.REQUEST_PUT_COMPANY,
);

export function fetchCompany(companyId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.companySetting;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchCompany());
    return getJson(`/companies/${companyId}.json`, status)()
      .then(json => dispatch(finishFetchCompany('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchCompany(new Error(message)));
      });
  };
}

export function putCompany(companyId, companyName, vision, mission) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isProcessing } = status.companySetting;
    if (isProcessing) {
      return Promise.resolve();
    }
    dispatch(requestPutCompany());
    return putJson(`/companies/${companyId}.json`, status)(null, { companyName, vision, mission })
      .then(json => dispatch(finishPutCompany('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishPutCompany(new Error(message)));
      });
  };
}
