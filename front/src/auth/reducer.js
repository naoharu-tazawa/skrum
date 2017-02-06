import { Action } from './action';

export default (state = {
  isFetching: false,
  isLoggedIn: false,
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
          isLoggedIn: false,
          token: null,
          error: {
            message: payload.message,
          },
        });
      }

      const token = payload.data;
      return Object.assign({}, state, {
        isFetching: false,
        isLoggedIn: true,
        token: {
          accessToken: token.access_token,
          tokenType: token.token_type,
        },
        error: null,
      });
    }

    case Action.REQUEST_LOGOUT:
    case Action.FORCE_LOGOUT:
      return Object.assign({}, state, {
        isLoggedIn: false,
        token: null,
        error: action.error,
      });

    default:
      return state;
  }
};
