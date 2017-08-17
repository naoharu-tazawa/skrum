import { Action } from './action';

export default (state = {
  isFetching: false,
  isAuthorized: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_LOGIN:
      return { ...state, isFetching: true };

    case Action.FINISH_LOGIN: {
      const { payload, error } = action;
      if (error) {
        return {
          ...state,
          isFetching: false,
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
        isFetching: false,
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

    default:
      return state;
  }
};
