import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_GET_POTENTIAL_LEADERS: 'REQUEST_GET_POTENTIAL_LEADERS',
  FINISH_GET_POTENTIAL_LEADERS: 'FINISH_GET_POTENTIAL_LEADERS',
};

const {
  requestGetPotentialLeaders,
  finishGetPotentialLeaders,
} = createActions({
  [Action.FINISH_GET_POTENTIAL_LEADERS]: keyValueIdentity,
},
  Action.REQUEST_GET_POTENTIAL_LEADERS,
);

export const getPotentialLeaders = groupId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.potentialLeaders.isSearching) return Promise.resolve();
    dispatch(requestGetPotentialLeaders());
    return getJson(`/groups/${groupId}/possibleleaders.json`, state)()
      .then(json => dispatch(finishGetPotentialLeaders('data', { groupId, data: json })))
      .catch(({ message }) => dispatch(finishGetPotentialLeaders(new Error(message))));
  };
