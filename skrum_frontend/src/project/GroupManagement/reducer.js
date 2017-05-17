import { Action } from './action';

export default (state = {
  isFetching: false,
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

    default:
      return state;
  }
};
