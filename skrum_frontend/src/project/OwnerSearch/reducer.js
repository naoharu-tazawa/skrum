import { Action } from './action';

export default (state = {
  isSearching: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_SEARCH_OWNER:
      return { ...state, isSearching: true };

    case Action.FINISH_SEARCH_OWNER: {
      if (error) {
        return { ...state, isSearching: false, error: { message: payload.message } };
      }
      return { ...state, isSearching: false, ...payload.data, error: null };
    }

    default:
      return state;
  }
};
