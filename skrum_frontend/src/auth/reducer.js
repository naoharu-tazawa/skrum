import { Action } from './action';

export default (state = {
  isFetching: false,
  isAuthorized: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_LOGIN:
      return Object.assign({}, state, {
        isFetching: true,
      });
    case Action.FINISH_LOGIN: {
      const { payload, error } = action;
      if (error) {
        return Object.assign({}, state, {
          isFetching: false,
          isAuthorized: false,
          token: null,
          error: {
            message: payload.message,
          },
        });
      }

      const { jwt } = payload.data;
      return Object.assign({}, state, {
        isFetching: false,
        isAuthorized: true,
        token: jwt,
        error: null,
      });
    }

    case Action.REQUEST_LOGOUT:
      return Object.assign({}, state, {
        isAuthorized: false,
        token: null,
        error: action.error,
      });

    default:
      return state;
  }
};
