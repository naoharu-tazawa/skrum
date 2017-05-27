import { values } from 'lodash';
import { Action } from './action';

export default (state = {
  isFetching: false,
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_OKR_DETAILS:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_OKR_DETAILS: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, ...values(payload)[0], error: null };
    }

    default:
      return state;
  }
};
