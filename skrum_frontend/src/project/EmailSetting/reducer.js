import { Action } from './action';

export default (state = {
  isFetching: false,
  isPutting: false,
  data: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_EMAIL_SETTINGS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_EMAIL_SETTINGS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, data: payload.data, error: null };
    }

    case Action.REQUEST_CHANGE_EMAIL_SETTINGS: {
      return { ...state, isPutting: true };
    }

    case Action.FINISH_CHANGE_EMAIL_SETTINGS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      return { ...state, data: payload.data, isPutting: false, error: null };
    }

    default:
      return state;
  }
};
