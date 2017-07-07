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

export const fetchCompany = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.companySetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompany());
    return getJson(`/companies/${companyId}.json`, state)()
      .then(json => dispatch(finishFetchCompany('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompany(new Error(message))));
  };

export const putCompany = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.companySetting.isPutting) return Promise.resolve();
    dispatch(requestPutCompany('data', { id: Number(id), ...data }));
    return putJson(`/companies/${id}.json`, state)(null, data)
      .then(json => dispatch(finishPutCompany('data', json)))
      .catch(({ message }) => dispatch(finishPutCompany(new Error(message))));
  };
