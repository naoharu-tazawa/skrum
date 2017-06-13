import { combineReducers } from 'redux';
import { reducer as formReducer } from 'redux-form';
import { routerReducer } from 'react-router-redux';
import { reducer as toastrReducer } from 'react-redux-toastr';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrReducer from '../../project/OKR/reducer';
import mapReducer from '../../project/Map/reducer';
import timelineReducer from '../../project/Timeline/reducer';
import groupManagementReducer from '../../project/GroupManagement/reducer';
import okrDetailsReducer from '../../project/OKRDetails/reducer';
import userSettingReducer from '../../project/UserSetting/reducer';
import groupSettingReducer from '../../project/GroupSetting/reducer';
import companyProfileReducer from '../../project/CompanyProfile/reducer';
import timeframeReducer from '../../project/Timeframe/reducer';
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
  userSetting: userSettingReducer,
  groupSetting: groupSettingReducer,
  companySetting: companyProfileReducer,
  timeframeSetting: timeframeReducer,
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
