import { Action } from './action';

export default (state = {
  isFetchingGroups: false,
  isPostingGroup: false,
  isDeletingGroup: false,
  count: 0,
  groups: [],
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_GROUPS:
      return { ...state, isFetchingGroups: true };

    case Action.FINISH_FETCH_GROUPS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetchingGroups: false, error: { message: payload.message } };
      }
      const { count, results: groups } = payload.data;
      return { ...state, isFetchingGroups: false, count, groups, error: null };
    }

    case Action.REQUEST_CREATE_GROUP:
      return { ...state, isPostingGroup: true };

    case Action.FINISH_CREATE_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingGroup: false, error: { message: payload.message } };
      }
      return { ...state, isPostingGroup: false, error: null };
    }

    case Action.REQUEST_DELETE_GROUP:
      return { ...state, isDeletingGroup: true };

    case Action.FINISH_DELETE_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeletingGroup: false, error: { message: payload.message } };
      }
      const { id } = payload.data;
      const groups = state.groups.filter(group => group.groupId !== id);
      return { ...state, groups, isDeletingGroup: false, error: null };
    }

    default:
      return state;
  }
};
