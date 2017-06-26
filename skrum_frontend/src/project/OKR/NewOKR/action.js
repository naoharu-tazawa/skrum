import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../../util/ActionUtil';
import { postJson } from '../../../util/ApiUtil';

export const Action = {
  REQUEST_POST_OKR: 'REQUEST_POST_OKR',
  FINISH_POST_OKR: 'FINISH_POST_OKR',
};

const {
  requestPostOkr,
  finishPostOkr,
} = createActions({
  [Action.FINISH_POST_OKR]: keyValueIdentity,
},
  Action.REQUEST_POST_OKR,
);

export function postOKR(okr) {
  return (dispatch, getStatus) => {
    const status = getStatus();
    const { isPosting } = status.timeline;
    if (isPosting) {
      return Promise.resolve();
    }
    dispatch(requestPostOkr());
    return postJson('/okrs.json', status)(null, okr)
      .then(json => dispatch(finishPostOkr('data', json)))
      .catch((err) => {
        const { message } = err;
        return dispatch(finishPostOkr(new Error(message)));
      });
  };
}
