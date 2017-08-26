import { Action } from './action';

export default (state = {
  isFetching: false,
  isPosting: false,
  data: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_TOP:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_USER_TOP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, data: payload.data, error: null };
    }

    case Action.REQUEST_SETUP_COMPANY:
    case Action.REQUEST_SETUP_USER:
      return { ...state, isPosting: true };

    case Action.FINISH_SETUP_COMPANY:
    case Action.FINISH_SETUP_USER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPosting: false, error: { message: payload.message } };
      }
      return { ...state, isPosting: false, error: null };
    }

    default:
      return state;
  }
};
