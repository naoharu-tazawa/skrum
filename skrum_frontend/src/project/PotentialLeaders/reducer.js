import { Action } from './action';

export default (state = {
  isSearching: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_GET_POTENTIAL_LEADERS:
      return { ...state, isSearching: true };

    case Action.FINISH_GET_POTENTIAL_LEADERS: {
      if (error) {
        return { ...state, isSearching: false, error: { message: payload.message } };
      }
      return { ...state, isSearching: false, ...payload.data, error: null };
    }

    default:
      return state;
  }
};
