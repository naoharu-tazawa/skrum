import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrReducer from '../../project/OKR/reducer';
import mapReducer from '../../project/Map/reducer';
import timelineReducer from '../../project/Timeline/reducer';
import groupManagementReducer from '../../project/GroupManagement/reducer';
import okrDetailsReducer from '../../project/OKR/OKRDetails/reducer';
import companyProfileReducer from '../../project/CompanyProfile/reducer';
import timeframeReducer from '../../project/Timeframe/reducer';
import passwordChangeReducer from '../../project/PasswordChange/reducer';

const rootReducer = combineReducers({
  auth: authReducer,
  top: navigationReducer,
  basics: okrReducer,
  map: mapReducer,
  groupManagement: groupManagementReducer,
  timeline: timelineReducer,
  okr: okrDetailsReducer,
  companySetting: companyProfileReducer,
  timeframeSetting: timeframeReducer,
  setting: passwordChangeReducer,
  routing: routerReducer,
});

export default rootReducer;
