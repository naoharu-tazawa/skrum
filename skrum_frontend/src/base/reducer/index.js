import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import userGroupReducer from '../../project/UserGroup/UserGroupReducer';

const rootReducer = combineReducers({
  auth: authReducer,
  user: navigationReducer,
  routing: routerReducer,
  userGroupData: userGroupReducer,
});

export default rootReducer;
