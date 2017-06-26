import { Action } from './action';

export default (state = {
  isPosting: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_POST_OKR:
      return { ...state, isPosting: true };

    case Action.FINISH_POST_OKR: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPosting: false, error: { message: payload.message } };
      }
      return { ...state, isPosting: false, data: payload.data, error: null };
    }

    default:
      return state;
  }
};
