import { Action } from './action';

export default (state = {
  isSearching: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_SEARCH_USER_GROUPS:
      return { ...state, isSearching: true };

    case Action.FINISH_SEARCH_USER_GROUPS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isSearching: false, error: { message: payload.message } };
      }
      return { ...state, isSearching: false, data: payload.userGroupsFound, error: null };
    }

    default:
      return state;
  }
};
