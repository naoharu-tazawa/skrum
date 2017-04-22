import { Action } from './action';

export default (state = {
  isFetching: false,
  data: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_USER_TOP:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_USER_TOP: {
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

    default:
      return state;
  }
};
