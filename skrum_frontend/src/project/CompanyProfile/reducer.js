import { Action } from './action';

export default (state = {
  isFetching: false,
  isPutting: false,
  data: {},
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

    case Action.REQUEST_PUT_COMPANY: {
      const { payload } = action;
      const { id, ...items } = payload.data;
      const { data } = state;
      const newCompanyData = { ...data, ...(id === data.companyId ? items : {}) };
      return { ...state, data: newCompanyData, isPutting: true };
    }

    case Action.FINISH_PUT_COMPANY: {
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
