import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../util/ActionUtil';

export const Action = {
  REFLECT_IMAGES_VERSION: 'REFLECT_IMAGES_VERSION',
};

const {
  reflectImagesVersion,
} = createActions({
  [Action.REFLECT_IMAGES_VERSION]: keyValueIdentity,
});

export const syncImagesVersion = images =>
  dispatch => dispatch(reflectImagesVersion(images));
