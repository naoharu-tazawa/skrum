import { Action } from './action';

export default (state = {
  isFetching: false,
  isPosting: false,
  data: [],
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_GROUP_POSTS:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_GROUP_POSTS: {
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
        data: payload.data,
        error: null,
      });
    }

    case Action.REQUEST_POST_GROUP_POSTS:
      return Object.assign({}, state, { isPosting: true });

    case Action.FINISH_POST_GROUP_POSTS: {
      const { payload, error } = action;
      if (error) {
        return Object.assign({}, state, {
          isPosting: false,
          error: {
            message: payload.message,
          },
        });
      }
      return Object.assign({}, state, {
        isPosting: false,
        data: [payload.data, ...state.data],
        error: null,
      });
    }

    default:
      return state;
  }
};
