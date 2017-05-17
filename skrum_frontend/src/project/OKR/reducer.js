import { Action } from './action';

export default (state = {
  isFetching: false,
  user: {},
  group: {},
  company: {},
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

    default:
      return state;
  }
};
