import { Action } from './action';

export default (state = {
  isFetching: false,
  isPosting: false,
  roles: [],
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_COMPANY_ROLES:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_COMPANY_ROLES: {
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
        roles: payload.roles,
        error: null,
      });
    }

    case Action.REQUEST_POST_INVITE:
      return Object.assign({}, state, { isPosting: true });

    case Action.FINISH_POST_INVITE: {
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
        error: null,
      });
    }

    default:
      return state;
  }
};
