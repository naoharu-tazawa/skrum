import { Action } from './action';

export default (state = {
  isPosting: false,
  isAuthorized: false,
  isPreregistering: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_LOGIN:
    case Action.REQUEST_USER_SIGN_UP:
    case Action.REQUEST_USER_JOIN:
      return { ...state, isPosting: true };

    case Action.FINISH_LOGIN:
    case Action.FINISH_USER_SIGN_UP:
    case Action.FINISH_USER_JOIN: {
      const { payload, error } = action;
      if (error) {
        return {
          ...state,
          isPosting: false,
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
        isPosting: false,
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

    case Action.REQUEST_PREREGISTER:
      return { ...state, isPreregistering: true };

    case Action.FINISH_PREREGISTER: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPreregistering: false, error: { message: payload.message } };
      }
      return { ...state, isPreregistering: false, preregistered: payload.data, error: null };
    }

    default:
      return state;
  }
};
