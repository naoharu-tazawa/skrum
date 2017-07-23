import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY_TIMEFRAMES: 'REQUEST_FETCH_COMPANY_TIMEFRAMES',
  FINISH_FETCH_COMPANY_TIMEFRAMES: 'FINISH_FETCH_COMPANY_TIMEFRAMES',
  REQUEST_PUT_TIMEFRAME: 'REQUEST_PUT_TIMEFRAME',
  FINISH_PUT_TIMEFRAME: 'FINISH_PUT_TIMEFRAME',
};

const {
  requestFetchCompanyTimeframes,
  finishFetchCompanyTimeframes,
  requestPutTimeframe,
  finishPutTimeframe,
} = createActions({
  [Action.FINISH_FETCH_COMPANY_TIMEFRAMES]: keyValueIdentity,
  [Action.FINISH_PUT_TIMEFRAME]: keyValueIdentity,
  [Action.REQUEST_PUT_TIMEFRAME]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY_TIMEFRAMES,
);

export const fetchCompanyTimeframes = companyId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isFetching) return Promise.resolve();
    dispatch(requestFetchCompanyTimeframes());
    return getJson(`/companies/${companyId}/timeframedetails.json`, state)()
      .then(json => dispatch(finishFetchCompanyTimeframes('data', json)))
      .catch(({ message }) => dispatch(finishFetchCompanyTimeframes(new Error(message))));
  };

export const putTimeframe = (id, data) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.timeframeSetting.isPutting) return Promise.resolve();
    dispatch(requestPutTimeframe('data', { id: Number(id), ...data }));
    return putJson(`/timeframes/${id}.json`, state)(null, data)
      .then(json => dispatch(finishPutTimeframe('data', json)))
      .catch(({ message }) => dispatch(finishPutTimeframe(new Error(message))));
  };