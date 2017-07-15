import { Action } from './action';

export default (state = {
  isFetching: false,
  user: {},
  group: {},
  company: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_OKRS:
    case Action.REQUEST_FETCH_GROUP_OKRS:
    case Action.REQUEST_FETCH_COMPANY_OKRS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_OKRS:
    case Action.FINISH_FETCH_GROUP_OKRS:
    case Action.FINISH_FETCH_COMPANY_OKRS: {
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
