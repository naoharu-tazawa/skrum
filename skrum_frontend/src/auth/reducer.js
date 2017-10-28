import { Action } from './action';

export default (state = {
  isAuthorized: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_LOGIN:
    case Action.REQUEST_USER_SIGN_UP:
    case Action.REQUEST_USER_JOIN:
    case Action.REQUEST_PREREGISTER:
      return state;

    case Action.FINISH_LOGIN:
    case Action.FINISH_USER_SIGN_UP:
    case Action.FINISH_USER_JOIN: {
      if (error) {
        return {
          ...state,
          isAuthorized: false,
          token: null,
          error: { message: payload.message },
        };
      }
      const { jwt: token } = payload.data;
      const splitJwt = token.split('.');
      const jwt = JSON.parse(window.atob(splitJwt[1]));
      const { uid: userId, cid: companyId, rid: roleId, rlv: roleLevel, permissions } = jwt;
      return {
        ...state,
        isAuthorized: true,
        token,
        userId,
        companyId,
        roleId,
        roleLevel,
        permissions,
        error: null,
      };
    }

    case Action.REQUEST_LOGOUT:
      return { ...state, isAuthorized: false, token: null, error };

    case Action.FINISH_PREREGISTER: {
      if (error) {
        return { ...state, error: { message: payload.message } };
      }
      return { ...state, preregistered: payload.data, error: null };
    }

    default:
      return state;
  }
};
