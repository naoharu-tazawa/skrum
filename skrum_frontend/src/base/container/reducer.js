import { Action } from './action';

export default (state = {
  images: {},
}, { type, payload }) => {
  switch (type) {
    case Action.REFLECT_IMAGES_VERSION: {
      const companies = { ...state.images.companies, ...payload.images.companies };
      const groups = { ...state.images.groups, ...payload.images.groups };
      const users = { ...state.images.users, ...payload.images.users };
      const images = { companies, groups, users };
      return { ...state, images };
    }

    default:
      return state;
  }
};
