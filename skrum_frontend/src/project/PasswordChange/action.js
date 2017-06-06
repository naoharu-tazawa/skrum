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

export function putUserChangepassword(userId, currentPassword, newPassword) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isProcessing } = status.setting;
    if (isProcessing) {
      return Promise.resolve();
    }
    dispatch(requestPutUserChangepassword());
    return putJson(`/users/${userId}/changepassword.json`, status)(null, { currentPassword, newPassword })
      .then(json => dispatch(finishPutUserChangepassword('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishPutUserChangepassword(new Error(message)));
      });
  };
}
