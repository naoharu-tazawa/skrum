import { values, fromPairs } from 'lodash';
import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';
import { toUtcDate } from '../../util/DatetimeUtil';

export default (state = {
  isFetching: false,
  isPutting: false,
  isPostingKR: false,
  isChangingParentOkr: false,
  isSettingRatios: false,
  isDeletingKR: false,
  isPostingAchievement: false,
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

    case Action.REQUEST_POST_KR:
      return { ...state, isPostingKR: true };

    case Action.FINISH_POST_KR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingKR: false, error: { message: payload.message } };
      }
      const { targetOkr: keyResult, parentOkr } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr;
      const objective = mergeUpdateById(state.objective, 'okrId', { achievementRate }, parentOkrId);
      const keyResults = [...state.keyResults, keyResult];
      const datetime = toUtcDate(new Date());
      const chart = parentOkrId !== state.objective.okrId ? state.chart :
        [...state.chart, { datetime, achievementRate }];
      return { ...state, objective, keyResults, chart, isPostingKR: false, error: null };
    }

    case Action.REQUEST_PUT_OKR_DETAILS:
    case Action.REQUEST_CHANGE_KR_OWNER:
    case Action.REQUEST_CHANGE_OKR_DISCLOSURE_TYPE:
      return { ...state, isPutting: true };

    case Action.FINISH_PUT_OKR_DETAILS:
    case Action.FINISH_CHANGE_KR_OWNER:
    case Action.FINISH_CHANGE_OKR_DISCLOSURE_TYPE: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { id, ...update } = payload.data;
      const objective = mergeUpdateById(state.objective, 'okrId', update, id);
      const keyResults = state.keyResults.map(kr => mergeUpdateById(kr, 'okrId', update, id));
      return { ...state, objective, keyResults, isPutting: false, error: null };
    }

    case Action.REQUEST_CHANGE_PARENT_OKR:
      return { ...state, isChangingParentOkr: true };

    case Action.FINISH_CHANGE_PARENT_OKR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isChangingParentOkr: false, error: { message: payload.message } };
      }
      return { ...state, ...payload.data, isChangingParentOkr: false, error: null };
    }

    case Action.REQUEST_DELETE_KR:
      return { ...state, isDeletingKR: true };

    case Action.FINISH_DELETE_KR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeletingKR: false, error: { message: payload.message } };
      }
      const { id, parentOkr } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr;
      const objective = mergeUpdateById(state.objective, 'okrId', { achievementRate }, parentOkrId);
      const keyResults = state.keyResults.filter(({ okrId }) => okrId !== id);
      const datetime = toUtcDate(new Date());
      const chart = parentOkrId !== state.objective.okrId ? state.chart :
        [...state.chart, { datetime, achievementRate }];
      return { ...state, objective, keyResults, chart, isDeletingKR: false, error: null };
    }

    case Action.REQUEST_POST_ACHIEVEMENT:
      return { ...state, isPostingAchievement: true };

    case Action.FINISH_POST_ACHIEVEMENT: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingAchievement: false, error: { message: payload.message } };
      }
      const { parentOkr, targetOkr } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr;
      const { okrId, ...update } = targetOkr;
      const parentObjective = mergeUpdateById(state.objective, 'okrId', { achievementRate }, parentOkrId);
      const objective = mergeUpdateById(parentObjective, 'okrId', update, okrId);
      const keyResults = state.keyResults.map(kr => mergeUpdateById(kr, 'okrId', update, okrId));
      const datetime = toUtcDate(new Date());
      const chart = parentOkrId !== state.objective.okrId ? state.chart :
        [...state.chart, { datetime, achievementRate }];
      return { ...state, objective, keyResults, chart, isPostingAchievement: false, error: null };
    }

    case Action.REQUEST_SET_RATIOS:
      return { ...state, isSettingRatios: true };

    case Action.FINISH_SET_RATIOS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isSettingRatios: false, error: { message: payload.message } };
      }
      const { parentOkr, ratios } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr;
      const objective = mergeUpdateById(state.objective, 'okrId', { achievementRate }, parentOkrId);
      const ratiosById = fromPairs(ratios.map(({ keyResultId, weightedAverageRatio }) =>
        ([keyResultId, { weightedAverageRatio }])));
      const keyResults = state.keyResults.map(kr =>
        ({ ...kr, ...ratiosById[kr.okrId], ratioLockedFlg: ratiosById[kr.okrId] ? 1 : 0 }));
      const datetime = toUtcDate(new Date());
      const chart = parentOkrId !== state.objective.okrId ? state.chart :
        [...state.chart, { datetime, achievementRate }];
      return { ...state, objective, keyResults, chart, isSettingRatios: false, error: null };
    }

    default:
      return state;
  }
};
