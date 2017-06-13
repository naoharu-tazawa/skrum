import { Action } from './action';

export default (state = {
  isFetching: false,
  data: [],
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_COMPANY_TIMEFRAMES:
      return Object.assign({}, state, { isFetching: true });

    case Action.FINISH_FETCH_COMPANY_TIMEFRAMES: {
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

    case Action.REQUEST_PUT_TIMEFRAME: {
      const { payload } = action;
      const { id, ...item } = payload.data;
      // const { data = [] } = state;
      // const newTimeframeList = data.map(value => (value.timeframeId === id ? value : item));
      console.log(id);
      console.log(item);
      // return { ...state, data: [...newTimeframeList], isPutting: true };
      return { ...state, isPutting: true };
    }

    case Action.FINISH_PUT_TIMEFRAME: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      return { ...state, isPutting: false, error: null };
    }

    default:
      return state;
  }
};
