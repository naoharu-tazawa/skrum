import { values } from 'lodash';
import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  isFetching: false,
  isPutting: false,
  isPostingKR: false,
  isDeletingKR: false,
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

    case Action.REQUEST_PUT_OKR_DETAILS:
      return { ...state, isPutting: true };

    case Action.FINISH_PUT_OKR_DETAILS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { id, ...data } = payload.data;
      const objective = mergeUpdateById(state.objective, 'okrId', data, id);
      const keyResults = state.keyResults.map(kr => mergeUpdateById(kr, 'okrId', data, id));
      return { ...state, objective, keyResults, isPutting: false };
    }

    case Action.REQUEST_POST_KR:
      return { ...state, isPostingKR: true };

    case Action.FINISH_POST_KR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingKR: false, error: { message: payload.message } };
      }
      const keyResults = [...state.keyResults, payload.newKR.data];
      return { ...state, keyResults, isPostingKR: false, error: null };
    }

    case Action.REQUEST_DELETE_KR:
      return { ...state, isDeletingKR: true };

    case Action.FINISH_DELETE_KR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeletingKR: false, error: { message: payload.message } };
      }
      const { id } = payload.deletedKR;
      const keyResults = state.keyResults.filter(({ okrId }) => id !== okrId);
      return { ...state, keyResults, isDeletingKR: false, error: null };
    }

    default:
      return state;
  }
};
