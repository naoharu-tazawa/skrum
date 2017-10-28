import { Action } from './action';

export default (state = {
  isFetchingGroups: false,
  isPostingGroup: false,
  isDeletingGroup: false,
  count: 0,
  groups: [],
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_FETCH_GROUPS:
      return { ...state, isFetchingGroups: true };

    case Action.FINISH_FETCH_GROUPS: {
      if (error) {
        return { ...state, isFetchingGroups: false, error: { message: payload.message } };
      }
      const { keyword, pageNo, count, results: groups } = payload.data;
      return { ...state, isFetchingGroups: false, keyword, pageNo, count, groups, error: null };
    }

    case Action.REQUEST_CREATE_GROUP:
      return { ...state, isPostingGroup: true };

    case Action.FINISH_CREATE_GROUP: {
      if (error) {
        return { ...state, isPostingGroup: false, error: { message: payload.message } };
      }
      return { ...state, isPostingGroup: false, error: null };
    }

    case Action.REQUEST_DELETE_GROUP:
      return { ...state, isDeletingGroup: true };

    case Action.FINISH_DELETE_GROUP: {
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
