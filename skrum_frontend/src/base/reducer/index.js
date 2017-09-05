import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import { reducer as formReducer } from 'redux-form';
import { reducer as toastrReducer } from 'react-redux-toastr';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrReducer from '../../project/OKR/reducer';
import mapReducer from '../../project/Map/reducer';
import timelineReducer from '../../project/Timeline/reducer';
import groupManagementReducer from '../../project/GroupManagement/reducer';
import okrDetailsReducer from '../../project/OKRDetails/reducer';
import okrSearchReducer from '../../project/OKRSearch/reducer';
import userGroupSearchReducer from '../../project/UserGroupSearch/reducer';
import groupUserSearchReducer from '../../project/GroupUserSearch/reducer';
import potentialLeadersReducer from '../../project/PotentialLeaders/reducer';
import ownerSearchReducer from '../../project/OwnerSearch/reducer';
import pathSearchReducer from '../../project/PathSearch/reducer';
import userSettingReducer from '../../project/UserSetting/reducer';
import groupSettingReducer from '../../project/GroupSetting/reducer';
import companyProfileReducer from '../../project/CompanyProfile/reducer';
import timeframeSettingReducer from '../../project/TimeframeSetting/reducer';
import emailSettingReducer from '../../project/EmailSetting/reducer';
import passwordChangeReducer from '../../project/PasswordChange/reducer';
import { Action } from '../../project/PasswordChange/action';

const rootReducer = combineReducers({
  auth: authReducer,
  top: navigationReducer,
  basics: okrReducer,
  map: mapReducer,
  groupManagement: groupManagementReducer,
  timeline: timelineReducer,
  okr: okrDetailsReducer,
  okrsFound: okrSearchReducer,
  userGroupsFound: userGroupSearchReducer,
  groupUsersFound: groupUserSearchReducer,
  potentialLeaders: potentialLeadersReducer,
  ownersFound: ownerSearchReducer,
  pathsFound: pathSearchReducer,
  userSetting: userSettingReducer,
  groupSetting: groupSettingReducer,
  companySetting: companyProfileReducer,
  timeframeSetting: timeframeSettingReducer,
  emailSetting: emailSettingReducer,
  setting: passwordChangeReducer,
  routing: routerReducer,
  form: formReducer.plugin({
    form: (state, action) => {
      switch (action.type) {
        case Action.FINISH_PUT_USER_CHANGEPASSWORD:
          return undefined;
        default:
          return state;
      }
    },
  }),
  toastr: toastrReducer,
});

export default rootReducer;
