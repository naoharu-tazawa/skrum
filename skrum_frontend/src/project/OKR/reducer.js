import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  user: {},
  group: {},
  company: {},
  isFetching: false,
  isPostingOkr: false,
  isDeletingOkr: false,
  isChangingOkrOwner: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_BASICS:
    case Action.REQUEST_FETCH_GROUP_BASICS:
    case Action.REQUEST_FETCH_COMPANY_BASICS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_BASICS:
    case Action.FINISH_FETCH_GROUP_BASICS:
    case Action.FINISH_FETCH_COMPANY_BASICS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, ...payload, error: null };
    }

    case Action.REQUEST_POST_OKR:
      return { ...state, isPostingOkr: true };

    case Action.FINISH_POST_OKR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingOkr: false, error: { message: payload.message } };
      }
      const { subject, isOwnerCurrent, data } = payload.data;
      const { parentOkrId } = data;
      const { [subject]: basics } = state;
      const okrs = (isOwnerCurrent ? [...basics.okrs, data] : basics.okrs).map(okr =>
        (okr.okrId === parentOkrId ? { ...okr, keyResults: [...okr.keyResults, data] } : okr));
      return { ...state, [subject]: { ...basics, okrs }, isPostingOkr: false, error: null };
    }

    case Action.REQUEST_DELETE_OKR:
      return { ...state, isDeletingOkr: true };

    case Action.FINISH_DELETE_OKR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeletingOkr: false, error: { message: payload.message } };
      }
      const { subject, id } = payload.data;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.filter(({ okrId }) => id !== okrId);
      return { ...state, [subject]: { ...basics, okrs }, isDeletingOkr: false, error: null };
    }

    case Action.REQUEST_CHANGE_OKR_OWNER:
      return { ...state, isChangingOkrOwner: true };

    case Action.FINISH_CHANGE_OKR_OWNER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isChangingOkrOwner: false, error: { message: payload.message } };
      }
      const { subject, id } = payload.data;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.filter(({ okrId }) => id !== okrId);
      return { ...state, [subject]: { ...basics, okrs }, isChangingOkrOwner: false, error: null };
    }

    case Action.SYNC_OKR_DETAILS: {
      const { payload, error } = action;
      if (error) {
        return state;
      }
      const { subject, id, okrId, ...data } = payload.data;
      const { [subject]: basics } = state;
      const okrs = basics.okrs.map(okr => mergeUpdateById(okr, 'okrId', data, id || okrId));
      return { ...state, [subject]: { ...basics, okrs } };
    }

    default:
      return state;
  }
};
