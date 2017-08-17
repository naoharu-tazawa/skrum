import { Action } from './action';

export default (state = {
  isSearching: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_SEARCH_PATH:
    case Action.REQUEST_SEARCH_ADDITIONAL_PATH:
      return { ...state, isSearching: true };

    case Action.FINISH_SEARCH_PATH:
    case Action.FINISH_SEARCH_ADDITIONAL_PATH: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isSearching: false, error: { message: payload.message } };
      }
      return { ...state, isSearching: false, data: payload.data, error: null };
    }

    default:
      return state;
  }
};
