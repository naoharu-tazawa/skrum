import { Action } from './action';

export default (state = {
  isFetching: false,
  user: {},
  group: {},
  company: {},
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_FETCH_USER_OKRS:
    case Action.REQUEST_FETCH_GROUP_OKRS:
    case Action.REQUEST_FETCH_COMPANY_OKRS:
    case Action.REQUEST_FETCH_OKR_DESCENDANTS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_OKRS:
    case Action.FINISH_FETCH_GROUP_OKRS:
    case Action.FINISH_FETCH_COMPANY_OKRS:
    case Action.FINISH_FETCH_OKR_DESCENDANTS: {
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, ...payload, error: null };
    }

    default:
      return state;
  }
};
