import { Action } from './action';

export default (state = {
  isProcessing: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_PUT_USER_CHANGEPASSWORD:
      return { ...state, isProcessing: true };

    case Action.FINISH_PUT_USER_CHANGEPASSWORD: {
      if (error) {
        return { ...state, isProcessing: false, error: { message: payload.message } };
      }
      return { ...state, isProcessing: false, error: null };
    }

    default:
      return state;
  }
};
