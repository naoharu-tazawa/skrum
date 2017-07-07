import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_PUT_USER_CHANGEPASSWORD: 'REQUEST_PUT_USER_CHANGEPASSWORD',
  FINISH_PUT_USER_CHANGEPASSWORD: 'FINISH_PUT_USER_CHANGEPASSWORD',
};

const {
  requestPutUserChangepassword,
  finishPutUserChangepassword,
} = createActions({
  [Action.FINISH_PUT_USER_CHANGEPASSWORD]: keyValueIdentity,
},
  Action.REQUEST_PUT_USER_CHANGEPASSWORD,
);

export const putUserChangepassword = (userId, currentPassword, newPassword) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.setting.isProcessing) return Promise.resolve();
    dispatch(requestPutUserChangepassword());
    return putJson(`/users/${userId}/changepassword.json`, state)(null, { currentPassword, newPassword })
      .then(json => dispatch(finishPutUserChangepassword('data', json)))
      .catch(({ message }) => dispatch(finishPutUserChangepassword(new Error(message))));
  };
