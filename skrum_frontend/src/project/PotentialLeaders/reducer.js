import { Action } from './action';

export default (state = {
  isSearching: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_GET_POTENTIAL_LEADERS:
      return { ...state, isSearching: true };

    case Action.FINISH_GET_POTENTIAL_LEADERS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isSearching: false, error: { message: payload.message } };
      }
      return { ...state, isSearching: false, ...payload.potentialLeaders, error: null };
    }

    default:
      return state;
  }
};
