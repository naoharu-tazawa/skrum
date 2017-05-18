import { Action } from './action';

export default (state = {
  isFetching: false,
  user: {},
  group: {},
  company: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_OBJECTIVES:
    case Action.REQUEST_FETCH_GROUP_OBJECTIVES:
    case Action.REQUEST_FETCH_COMPANY_OBJECTIVES:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_OBJECTIVES:
    case Action.FINISH_FETCH_GROUP_OBJECTIVES:
    case Action.FINISH_FETCH_COMPANY_OBJECTIVES: {
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
