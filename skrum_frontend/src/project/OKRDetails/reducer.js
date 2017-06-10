import { values } from 'lodash';
import { Action } from './action';

export default (state = {
  isFetching: false,
  isPutting: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_OKR_DETAILS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_OKR_DETAILS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      const newOkr = { parentOkr: null, ...values(payload)[0] };
      return { ...state, isFetching: false, ...newOkr, error: null };
    }

    case Action.REQUEST_PUT_OKR_DETAILS: {
      const { payload } = action;
      const { id, ...data } = payload.data;
      const { objective, keyResults } = state;
      const newObjective = { ...objective, ...(id === objective.okrId ? data : {}) };
      const newKeyResults = keyResults.map(kr => (
        { ...kr, ...(id === kr.okrId ? data : {}) }));
      return { ...state, objective: newObjective, keyResults: newKeyResults, isPutting: true };
    }

    case Action.FINISH_PUT_OKR_DETAILS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      return { ...state, isPutting: false, error: null };
    }

    default:
      return state;
  }
};
