import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import { reducer as formReducer } from 'redux-form';
import { reducer as toastrReducer } from 'react-redux-toastr';
import { isArray, isObject, values, every, isEmpty } from 'lodash';
import baseReducer from '../container/reducer';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrBasicsReducer from '../../project/OKR/reducer';
import okrDetailsReducer from '../../project/OKRDetails/reducer';
import mapReducer from '../../project/Map/reducer';
import timelineReducer from '../../project/Timeline/reducer';
import oneOnOneReducer from '../../project/OneOnOne/reducer';
import groupManagementReducer from '../../project/GroupManagement/reducer';
import okrSearchReducer from '../../project/OKRSearch/reducer';
import userGroupSearchReducer from '../../project/UserGroupSearch/reducer';
import groupUserSearchReducer from '../../project/GroupUserSearch/reducer';
import potentialLeadersReducer from '../../project/PotentialLeaders/reducer';
import ownerSearchReducer from '../../project/OwnerSearch/reducer';
import pathSearchReducer from '../../project/PathSearch/reducer';
import userSearchReducer from '../../project/UserSearch/reducer';
import userSettingReducer from '../../project/UserSetting/reducer';
import groupSettingReducer from '../../project/GroupSetting/reducer';
import companyProfileReducer from '../../project/CompanyProfile/reducer';
import timeframeSettingReducer from '../../project/TimeframeSetting/reducer';
import initialDataReducer from '../../project/InitialDataUpload/reducer';
import emailSettingReducer from '../../project/EmailSetting/reducer';
import passwordChangeReducer from '../../project/PasswordChange/reducer';
import { Action as BaseAction } from '../container/action';
import { Action as AuthAction } from '../../auth/action';
import { Action as PasswordChangeAction } from '../../project/PasswordChange/action';

const appReducer = combineReducers({
  base: baseReducer,
  auth: authReducer,
  top: navigationReducer,
  basics: okrBasicsReducer,
  okr: okrDetailsReducer,
  map: mapReducer,
  timeline: timelineReducer,
  oneOnOne: oneOnOneReducer,
  groupManagement: groupManagementReducer,
  okrsFound: okrSearchReducer,
  userGroupsFound: userGroupSearchReducer,
  groupUsersFound: groupUserSearchReducer,
  potentialLeaders: potentialLeadersReducer,
  ownersFound: ownerSearchReducer,
  pathsFound: pathSearchReducer,
  usersFound: userSearchReducer,
  userSetting: userSettingReducer,
  groupSetting: groupSettingReducer,
  companySetting: companyProfileReducer,
  timeframeSetting: timeframeSettingReducer,
  initialData: initialDataReducer,
  emailSetting: emailSettingReducer,
  setting: passwordChangeReducer,
  routing: routerReducer,
  form: formReducer.plugin({
    form: (state, action) => {
      switch (action.type) {
        case PasswordChangeAction.FINISH_PUT_USER_CHANGEPASSWORD:
          return undefined;
        default:
          return state;
      }
    },
  }),
  toastr: toastrReducer,
});

const extractImagesVersion = (images = { companies: {}, groups: {}, users: {} }, data) => {
  const { imageVersion } = data;
  if (imageVersion !== undefined) {
    const { companyId: coId, groupId, userId } = data;
    if (coId) images = { ...images, companies: { ...images.companies, [coId]: imageVersion } };
    if (groupId) images = { ...images, groups: { ...images.groups, [groupId]: imageVersion } };
    if (userId) images = { ...images, users: { ...images.users, [userId]: imageVersion } };
  }
  const prefixes = ['owner', 'poster', 'sender'];
  prefixes.forEach((prefix) => {
    const {
      [`${prefix}CompanyImageVersion`]: companyImageVersion,
      [`${prefix}GroupImageVersion`]: groupImageVersion,
      [`${prefix}UserImageVersion`]: userImageVersion,
    } = data;
    const version = companyImageVersion || groupImageVersion || userImageVersion;
    if (version !== undefined) {
      const {
        [`${prefix}CompanyId`]: coId,
        [`${prefix}GroupId`]: groupId,
        [`${prefix}UserId`]: userId,
      } = data;
      if (coId) images = { ...images, companies: { ...images.companies, [coId]: version } };
      if (groupId) images = { ...images, groups: { ...images.groups, [groupId]: version } };
      if (userId) images = { ...images, users: { ...images.users, [userId]: version } };
    }
  });
  const objects = isArray(data) ? data : values(data).filter(isObject);
  // console.log({ data, images, objects });
  return objects.length ? objects.reduce(extractImagesVersion, images) : images;
};

// https://stackoverflow.com/questions/35622588/how-to-reset-the-state-of-a-redux-store
const rootReducer = (state, action) => {
  // console.log({ state, action });
  const { payload } = action;
  if (payload) {
    const images = extractImagesVersion(undefined, payload);
    if (!every(values(images), isEmpty)) {
      // console.log({ type: action.type, payload, images });
      state = appReducer(state,
        { type: BaseAction.REFLECT_IMAGES_VERSION, payload: { images } });
    }
  }
  return appReducer(action.type === AuthAction.REQUEST_LOGOUT ? undefined : state, action);
};

export default rootReducer;
