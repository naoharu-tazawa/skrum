import { Action } from './action';

export default (state = {
  isFetching: false,
  isPutting: false,
  user: {},
  group: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_GROUPS:
    case Action.REQUEST_FETCH_GROUP_MEMBERS:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_USER_GROUPS:
    case Action.FINISH_FETCH_GROUP_MEMBERS: {
      const { payload, error } = action;
      if (error) {
        return Object.assign({}, state, {
          isFetching: false,
          error: {
            message: payload.message,
          },
        });
      }
      return Object.assign({}, state, {
        isFetching: false,
        ...payload,
        error: null,
      });
    }

    case Action.REQUEST_PUT_USER: {
      const { payload } = action;
      const { id, ...data } = payload.data;
      const { user, groups } = state.user;
      const newUser = { ...user, ...(id === user.userId ? data : {}) };
      return { ...state, user: { user: newUser, groups }, isPutting: true };
    }

    case Action.REQUEST_PUT_GROUP: {
      const { payload } = action;
      const { id, ...data } = payload.data;
      const { group, members } = state.group;
      const newGroup = { ...group, ...(id === group.groupId ? data : {}) };
      return { ...state, group: { group: newGroup, members }, isPutting: true };
    }

    case Action.FINISH_PUT_USER:
    case Action.FINISH_PUT_GROUP: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      return { ...state, isPutting: false, error: null };
    }

    default:
      return state;
  }
};
