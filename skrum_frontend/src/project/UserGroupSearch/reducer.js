import { Action } from './action';

export default (state = {
  isSearching: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_SEARCH_USER_GROUPS:
      return { ...state, isSearching: true };

    case Action.FINISH_SEARCH_USER_GROUPS: {
      if (error) {
        return { ...state, isSearching: false, error: { message: payload.message } };
      }
      return { ...state, isSearching: false, data: payload.data, error: null };
    }

    default:
      return state;
  }
};
