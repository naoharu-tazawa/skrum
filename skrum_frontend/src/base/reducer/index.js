import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';
import authReducer from '../../auth/reducer';
import sampleReducer from '../../sample/reducer';

const rootReducer = combineReducers({
  auth: authReducer,
  sample: sampleReducer,
  routing: routerReducer,
});

export default rootReducer;
