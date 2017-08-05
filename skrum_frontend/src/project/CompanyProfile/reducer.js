import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  isFetching: false,
  isPutting: false,
  isPostingImage: false,
  data: {},
}, action) => {
  switch (action.type) {
    case Action.REQUEST_FETCH_COMPANY:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_COMPANY: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, data: payload.data, error: null };
    }

    case Action.REQUEST_PUT_COMPANY: {
      return { ...state, isPutting: true };
    }

    case Action.FINISH_PUT_COMPANY: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { id, ...items } = payload.data;
      const newCompanyData = mergeUpdateById(state.data, 'companyId', items, id);
      return { ...state, data: newCompanyData, isPutting: false, error: null };
    }

    case Action.REQUEST_POST_COMPANY_IMAGE:
      return { ...state, isPostingImage: true };

    case Action.FINISH_POST_COMPANY_IMAGE: {
      const { payload, error } = action;
      if (error) {
        return { ...state, isPostingImage: false, error: { message: payload.message } };
      }
      return { ...state, isPostingImage: false, error: null };
    }

    default:
      return state;
  }
};
