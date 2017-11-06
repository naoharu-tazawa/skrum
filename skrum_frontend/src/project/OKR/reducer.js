import { fromPairs } from 'lodash';
import { Action } from './action';

export default (state = {
  user: {},
  group: {},
  company: {},
  isFetching: false,
  isPostingOkr: false,
  isPutting: false,
  isChangingParentOkr: false,
  isPostingAchievement: false,
  isSettingRatios: false,
  isCopyingOkr: false,
  isDeletingOkr: false,
  isDeletingKR: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_FETCH_USER_BASICS:
    case Action.REQUEST_FETCH_GROUP_BASICS:
    case Action.REQUEST_FETCH_COMPANY_BASICS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_BASICS:
    case Action.FINISH_FETCH_GROUP_BASICS:
    case Action.FINISH_FETCH_COMPANY_BASICS: {
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, ...payload, error: null };
    }

    case Action.REQUEST_POST_OKR:
      return { ...state, isPostingOkr: true };

    case Action.FINISH_POST_OKR: {
      if (error) {
        return { ...state, isPostingOkr: false, error: { message: payload.message } };
      }
      const { subject, isOwnerCurrent, parentOkr, targetOkr } = payload.data;
      const { parentOkrId } = targetOkr;
      const { [subject]: basics } = state;
      const okrs = (isOwnerCurrent ? [...basics.okrs, targetOkr] : basics.okrs || []).map(okr =>
        (okr.okrId === parentOkrId ? {
          ...okr,
          ...parentOkr,
          keyResults: [...(okr.keyResults || []), targetOkr],
        } : okr));
      return { ...state, [subject]: { ...basics, okrs }, isPostingOkr: false, error: null };
    }

    case Action.REQUEST_CHANGE_OKR_OWNER:
    case Action.REQUEST_BASICS_CHANGE_DISCLOSURE_TYPE:
      return { ...state, isPutting: true };

    case Action.FINISH_CHANGE_OKR_OWNER:
    case Action.FINISH_BASICS_CHANGE_DISCLOSURE_TYPE:
    case Action.SYNC_BASICS_DETAILS: {
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { subject, id = payload.data.okrId, ...data } = payload.data;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.map(okr => ({
        ...okr,
        ...(okr.okrId === id ? data : {}),
        keyResults: (okr.keyResults || []).map(kr =>
          ({ ...kr, ...(kr.okrId === id ? data : {}) })),
      }));
      return { ...state, [subject]: { ...basics, okrs }, isPutting: false, error: null };
    }

    case Action.REQUEST_BASICS_CHANGE_PARENT_OKR:
      return { ...state, isChangingParentOkr: true };

    case Action.FINISH_BASICS_CHANGE_PARENT_OKR: {
      if (error) {
        return { ...state, isChangingParentOkr: false, error: { message: payload.message } };
      }
      const { subject, objective, parentOkr } = payload.data;
      const { okrId } = objective;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.map(okr =>
        (okr.okrId === okrId ? { ...okr, ...objective, parentOkr } : okr));
      return { ...state, [subject]: { ...basics, okrs }, isChangingParentOkr: false, error: null };
    }

    case Action.REQUEST_BASICS_POST_ACHIEVEMENT:
      return { ...state, isPostingAchievement: true };

    case Action.FINISH_BASICS_POST_ACHIEVEMENT: {
      if (error) {
        return { ...state, isPostingAchievement: false, error: { message: payload.message } };
      }
      const { subject, parentOkr, targetOkr } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr || {};
      const { okrId, ...update } = targetOkr;
      const { [subject]: basics } = state;
      const okrsPhase1 = basics.okrs.map(okr =>
        (okr.okrId === parentOkrId ? {
          ...okr,
          achievementRate,
          keyResults: (okr.keyResults || []).map(kr =>
            ({ ...kr, ...(kr.okrId === okrId ? update : {}) })),
        } : okr));
      const okrs = okrsPhase1.map(okr =>
        (okr.okrId === okrId ? {
          ...okr,
          ...update,
        } : okr));
      return { ...state, [subject]: { ...basics, okrs }, isPostingAchievement: false, error: null };
    }

    case Action.REQUEST_BASICS_SET_RATIOS:
      return { ...state, isSettingRatios: true };

    case Action.FINISH_BASICS_SET_RATIOS: {
      if (error) {
        return { ...state, isSettingRatios: false, error: { message: payload.message } };
      }
      const { subject, parentOkr, ratios, unlockedRatio } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr;
      const ratiosById = fromPairs(ratios.map(({ keyResultId, weightedAverageRatio }) =>
        ([keyResultId, { weightedAverageRatio }])));
      const { [subject]: basics } = state;
      const okrs = basics.okrs.map(okr =>
        (okr.okrId === parentOkrId ? {
          ...okr,
          achievementRate,
          keyResults: (okr.keyResults || []).map(kr =>
            ({ ...kr,
              weightedAverageRatio: unlockedRatio,
              ...ratiosById[kr.okrId],
              ratioLockedFlg: ratiosById[kr.okrId] ? 1 : 0 })),
        } : okr));
      return { ...state, [subject]: { ...basics, okrs }, isSettingRatios: false, error: null };
    }

    case Action.REQUEST_COPY_OKR:
      return { ...state, isCopyingOkr: true };

    case Action.FINISH_COPY_OKR: {
      if (error) {
        return { ...state, isCopyingOkr: false, error: { message: payload.message } };
      }
      return { ...state, isCopyingOkr: false, error: null };
    }

    case Action.REQUEST_DELETE_OKR:
      return { ...state, isDeletingOkr: true };

    case Action.FINISH_DELETE_OKR: {
      if (error) {
        return { ...state, isDeletingOkr: false, error: { message: payload.message } };
      }
      const { subject, id } = payload.data;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.filter(({ okrId }) => okrId !== id);
      return { ...state, [subject]: { ...basics, okrs }, isDeletingOkr: false, error: null };
    }

    case Action.REQUEST_BASICS_DELETE_KR:
      return { ...state, isDeletingKR: true };

    case Action.FINISH_BASICS_DELETE_KR: {
      if (error) {
        return { ...state, isDeletingKR: false, error: { message: payload.message } };
      }
      const { subject, id = payload.data.okrId, parentOkr } = payload.data;
      const { okrId: parentOkrId, achievementRate } = parentOkr;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.map(okr =>
        (okr.okrId === parentOkrId ? {
          ...okr,
          achievementRate,
          keyResults: (okr.keyResults || []).filter(({ okrId }) => okrId !== id),
        } : okr));
      return { ...state, [subject]: { ...basics, okrs }, isDeletingKR: false, error: null };
    }

    default:
      return state;
  }
};
