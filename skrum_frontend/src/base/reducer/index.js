import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';

const rootReducer = combineReducers({
  auth: authReducer,
  user: navigationReducer,
  routing: routerReducer,
});

export default rootReducer;
