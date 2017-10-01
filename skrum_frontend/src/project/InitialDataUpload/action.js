import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { postJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_POST_CSV: 'REQUEST_POST_CSV',
  FINISH_POST_CSV: 'FINISH_POST_CSV',
};

const {
  requestPostCsv,
  finishPostCsv,
} = createActions({
  [Action.FINISH_POST_CSV]: keyValueIdentity,
},
  Action.REQUEST_POST_CSV,
);

export const postCsv = content =>
  (dispatch, getState) => {
    const state = getState();
    if (state.initialData.isPosting) return Promise.resolve();
    dispatch(requestPostCsv());
    const mimeType = 'text/csv';
    return postJson('/csv/additionalusers.json', state)(null, { mimeType, content })
      .then(json => dispatch(finishPostCsv('data', json)))
      .catch(({ message }) => dispatch(finishPostCsv(new Error(message))));
  };
