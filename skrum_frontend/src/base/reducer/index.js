import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import navigationReducer from '../../navigation/reducer';
import okrReducer from '../../project/OKR/reducer';

const rootReducer = combineReducers({
  auth: authReducer,
  top: navigationReducer,
  basics: okrReducer,
  routing: routerReducer,
});

export default rootReducer;
