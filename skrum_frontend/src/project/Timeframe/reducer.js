import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  isFetching: false,
  isPutting: false,
  isPosting: false,
  isDefaulting: false,
  isDeleting: false,
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
      return { ...state, isPutting: true };
    }

    case Action.FINISH_PUT_TIMEFRAME: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { id, ...update } = payload.data;
      const data = state.data.map(tf => mergeUpdateById(tf, 'timeframeId', update, id));
      return { ...state, data, isPutting: true, error: null };
    }

    case Action.REQUEST_POST_TIMEFRAME:
      return { ...state, isPosting: true };

    case Action.FINISH_POST_TIMEFRAME: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPosting: false, error: { message: payload.message } };
      }
      const data = [payload.data.data, ...state.data];
      return { ...state, data, isPosting: false, error: null };
    }

    case Action.REQUEST_DEFAULT_TIMEFRAME: {
      return { ...state, isDefaulting: true };
    }

    case Action.FINISH_DEFAULT_TIMEFRAME: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDefaulting: false, error: { message: payload.message } };
      }
      const { id } = payload.data;
      // eslint-disable-next-line no-unused-vars
      const data = state.data.map(({ defaultFlg, ...tf }) =>
        ({ ...tf, ...(tf.timeframeId === id ? { defaultFlg: 1 } : {}) }));
      return { ...state, data, isDefaulting: false, error: null };
    }

    case Action.REQUEST_DELETE_TIMEFRAME:
      return { ...state, isDeleting: true };

    case Action.FINISH_DELETE_TIMEFRAME: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isDeleting: false, error: { message: payload.message } };
      }
      const { id } = payload.data;
      const data = state.data.filter(tf => tf.timeframeId !== id);
      return { ...state, data, isDeleting: false, error: null };
    }

    default:
      return state;
  }
};
