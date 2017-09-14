import { Action } from './action';

export default (state = {
  isAuthorized: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_LOGIN:
    case Action.REQUEST_USER_SIGN_UP:
    case Action.REQUEST_USER_JOIN:
    case Action.REQUEST_PREREGISTER:
      return state;

    case Action.FINISH_LOGIN:
    case Action.FINISH_USER_SIGN_UP:
    case Action.FINISH_USER_JOIN: {
      const { payload, error } = action;
      if (error) {
        return {
          ...state,
          isAuthorized: false,
          token: null,
          error: { message: payload.message },
        };
      }
      const { jwt } = payload.data;
      const splitedJwt = jwt.split('.');
      const jwtPayload = JSON.parse(window.atob(splitedJwt[1]));
      return {
        ...state,
        isAuthorized: true,
        token: jwt,
        userId: jwtPayload.uid,
        companyId: jwtPayload.cid,
        roleId: jwtPayload.rid,
        roleLevel: jwtPayload.rlv,
        permissions: jwtPayload.permissions,
        error: null,
      };
    }

    case Action.REQUEST_LOGOUT:
      return { ...state, isAuthorized: false, token: null, error: action.error };

    case Action.FINISH_PREREGISTER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, error: { message: payload.message } };
      }
      return { ...state, preregistered: payload.data, error: null };
    }

    default:
      return state;
  }
};
