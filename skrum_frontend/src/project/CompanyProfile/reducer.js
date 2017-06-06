import { Action } from './action';

export default (state = {
  isFetching: false,
  isProcessing: false,
  data: [],
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_COMPANY:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_COMPANY: {
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

    case Action.REQUEST_POST_COMPANY:
      return Object.assign({}, state, { isProcessing: true });

    case Action.FINISH_POST_COMPANY: {
      const { payload, error } = action;
      if (error) {
        return Object.assign({}, state, {
          isProcessing: false,
          error: {
            message: payload.message,
          },
        });
      }
      return Object.assign({}, state, {
        isProcessing: false,
        data: [payload.data, ...state.data],
        error: null,
      });
    }

    default:
      return state;
  }
};
