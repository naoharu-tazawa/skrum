import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrReducer from '../../project/OKR/reducer';
import userGroupReducer from '../../project/UserGroup/UserGroupReducer';

const rootReducer = combineReducers({
  auth: authReducer,
  top: navigationReducer,
  basics: okrReducer,
  userGroupData: userGroupReducer,
  routing: routerReducer,
});

export default rootReducer;
