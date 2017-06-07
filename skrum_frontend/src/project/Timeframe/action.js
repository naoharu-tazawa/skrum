import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_COMPANY_TIMEFRAMES: 'REQUEST_FETCH_COMPANY_TIMEFRAMES',
  FINISH_FETCH_COMPANY_TIMEFRAMES: 'FINISH_FETCH_COMPANY_TIMEFRAMES',
};

const {
  requestFetchCompanyTimeframes,
  finishFetchCompanyTimeframes,
} = createActions({
  [Action.FINISH_FETCH_COMPANY_TIMEFRAMES]: keyValueIdentity,
},
  Action.REQUEST_FETCH_COMPANY_TIMEFRAMES,
);

export function fetchCompanyTimeframes(companyId) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.timeframeSetting;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(requestFetchCompanyTimeframes());
    return getJson(`/companies/${companyId}/timeframedetails.json`, status)()
      .then(json => dispatch(finishFetchCompanyTimeframes('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishFetchCompanyTimeframes(new Error(message)));
      });
  };
}
