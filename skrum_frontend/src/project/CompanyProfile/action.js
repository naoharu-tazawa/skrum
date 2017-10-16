import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson, postJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY: 'REQUEST_FETCH_COMPANY',
  FINISH_FETCH_COMPANY: 'FINISH_FETCH_COMPANY',
  REQUEST_PUT_COMPANY: 'REQUEST_PUT_COMPANY',
  FINISH_PUT_COMPANY: 'FINISH_PUT_COMPANY',
  REQUEST_POST_COMPANY_IMAGE: 'REQUEST_POST_COMPANY_IMAGE',
  FINISH_POST_COMPANY_IMAGE: 'FINISH_POST_COMPANY_IMAGE',
};

const {
  requestFetchCompany,
  finishFetchCompany,
  requestPutCompany,
  finishPutCompany,
  requestPostCompanyImage,
  finishPostCompanyImage,
} = createActions({
  [Action.FINISH_FETCH_COMPANY]: keyValueIdentity,
  [Action.FINISH_PUT_COMPANY]: keyValueIdentity,
  [Action.FINISH_POST_COMPANY_IMAGE]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY,
  Action.REQUEST_PUT_COMPANY,
  Action.REQUEST_POST_COMPANY_IMAGE,
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

export const putCompany = (companyId, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.companySetting.isPutting) return Promise.resolve();
    dispatch(requestPutCompany());
    return putJson(`/companies/${companyId}.json`, state)(null, data)
      .then(() => dispatch(finishPutCompany('data', { companyId, ...data })))
      .catch(({ message }) => dispatch(finishPutCompany(new Error(message))));
  };

export const postCompanyImage = (companyId, image, mimeType) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.companySetting.isPostingImage) return Promise.resolve();
    dispatch(requestPostCompanyImage());
    return postJson(`/companies/${companyId}/images.json`, state)(null, { image, mimeType })
      .then(json => dispatch(finishPostCompanyImage('data', { companyId, ...json })))
      .catch(({ message }) => dispatch(finishPostCompanyImage(new Error(message))));
  };
