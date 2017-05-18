import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_USER_OBJECTIVES: 'REQUEST_FETCH_USER_OBJECTIVES',
  REQUEST_FETCH_GROUP_OBJECTIVES: 'REQUEST_FETCH_GROUP_OBJECTIVES',
  REQUEST_FETCH_COMPANY_OBJECTIVES: 'REQUEST_FETCH_COMPANY_OBJECTIVES',
  FINISH_FETCH_USER_OBJECTIVES: 'FINISH_FETCH_USER_OBJECTIVES',
  FINISH_FETCH_GROUP_OBJECTIVES: 'FINISH_FETCH_GROUP_OBJECTIVES',
  FINISH_FETCH_COMPANY_OBJECTIVES: 'FINISH_FETCH_COMPANY_OBJECTIVES',
};

const {
  requestFetchUserObjectives,
  requestFetchGroupObjectives,
  requestFetchCompanyObjectives,
  finishFetchUserObjectives,
  finishFetchGroupObjectives,
  finishFetchCompanyObjectives,
} = createActions({
  [Action.FINISH_FETCH_USER_OBJECTIVES]: keyValueIdentity,
  [Action.FINISH_FETCH_GROUP_OBJECTIVES]: keyValueIdentity,
  [Action.FINISH_FETCH_COMPANY_OBJECTIVES]: keyValueIdentity,
},
  Action.REQUEST_FETCH_USER_OBJECTIVES,
  Action.REQUEST_FETCH_GROUP_OBJECTIVES,
  Action.REQUEST_FETCH_COMPANY_OBJECTIVES,
);

const fetchBasics = (section, node, request, finish) => (id, timeframeId) =>
  (dispatch, getStatus) => {
    const status = getStatus();
    const { isFetching } = status.map;
    if (isFetching) {
      return Promise.resolve();
    }
    dispatch(request());
    return getJson(`/${section}/${id}/objectives.json`, status)({ tfid: timeframeId })
      .then(json => dispatch(finish(node, json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finish(new Error(message)));
      });
  };

export const fetchUserObjectives = (id, timeframeId) =>
  fetchBasics('users', 'user', requestFetchUserObjectives, finishFetchUserObjectives)(id, timeframeId);

export const fetchGroupObjectives = (id, timeframeId) =>
  fetchBasics('groups', 'group', requestFetchGroupObjectives, finishFetchGroupObjectives)(id, timeframeId);

export const fetchCompanyObjectives = (id, timeframeId) =>
  fetchBasics('companies', 'company', requestFetchCompanyObjectives, finishFetchCompanyObjectives)(id, timeframeId);
