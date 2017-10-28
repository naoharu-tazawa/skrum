import { Action } from './action';

export default (state = {
  isPosting: false,
}, { type: actionType, payload }) => {
  switch (actionType) {
    case Action.REQUEST_POST_CSV:
      return { ...state, isPosting: true, error: null };

    case Action.FINISH_POST_CSV: {
      const { error } = payload.data;
      if (error) {
        return { ...state, isPosting: false, error: { message: error } };
      }
      return { ...state, isPosting: false, error: null };
    }

    default:
      return state;
  }
};
