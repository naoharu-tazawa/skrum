import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrReducer from '../../project/OKR/reducer';
import timelineReducer from '../../project/Timeline/reducer';
import groupManagementReducer from '../../project/GroupManagement/reducer';

const rootReducer = combineReducers({
  auth: authReducer,
  top: navigationReducer,
  basics: okrReducer,
  groupManagement: groupManagementReducer,
  timeline: timelineReducer,
  routing: routerReducer,
});

export default rootReducer;
