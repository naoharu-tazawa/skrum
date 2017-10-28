import { Action } from './action';
import { mergeUpdateById } from '../../util/ActionUtil';

export default (state = {
  isFetching: false,
  isPutting: false,
  isPostingImage: false,
  data: {},
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_FETCH_COMPANY:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_COMPANY: {
      if (error) {
        return { ...state, isFetching: false, error: { message: payload.message } };
      }
      return { ...state, isFetching: false, data: payload.data, error: null };
    }

    case Action.REQUEST_PUT_COMPANY: {
      return { ...state, isPutting: true };
    }

    case Action.FINISH_PUT_COMPANY: {
      if (error) {
        return { ...state, isPutting: false, error: { message: payload.message } };
      }
      const { companyId, ...items } = payload.data;
      const newCompanyData = mergeUpdateById(state.data, 'companyId', items, companyId);
      return { ...state, data: newCompanyData, isPutting: false, error: null };
    }

    case Action.REQUEST_POST_COMPANY_IMAGE:
      return { ...state, isPostingImage: true };

    case Action.FINISH_POST_COMPANY_IMAGE: {
      if (error) {
        return { ...state, isPostingImage: false, error: { message: payload.message } };
      }
      return { ...state, isPostingImage: false, error: null };
    }

    default:
      return state;
  }
};
