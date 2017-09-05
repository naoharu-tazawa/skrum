import { createActions } from 'redux-actions';
import { keyValueIdentity } from '../../util/ActionUtil';
import { getJson, putJson } from '../../util/ApiUtil';

export const Action = {
  REQUEST_FETCH_EMAIL_SETTINGS: 'REQUEST_FETCH_EMAIL_SETTINGS',
  FINISH_FETCH_EMAIL_SETTINGS: 'FINISH_FETCH_EMAIL_SETTINGS',
  REQUEST_CHANGE_EMAIL_SETTINGS: 'REQUEST_CHANGE_EMAIL_SETTINGS',
  FINISH_CHANGE_EMAIL_SETTINGS: 'FINISH_CHANGE_EMAIL_SETTINGS',
};

const {
  requestFetchEmailSettings,
  finishFetchEmailSettings,
  requestChangeEmailSettings,
  finishChangeEmailSettings,
} = createActions({
  [Action.FINISH_FETCH_EMAIL_SETTINGS]: keyValueIdentity,
  [Action.FINISH_CHANGE_EMAIL_SETTINGS]: keyValueIdentity,
},
  Action.REQUEST_FETCH_EMAIL_SETTINGS,
  Action.REQUEST_CHANGE_EMAIL_SETTINGS,
);

export const fetchEmailSettings = userId =>
  (dispatch, getState) => {
    const state = getState();
    if (state.emailSetting.isFetching) return Promise.resolve();
    dispatch(requestFetchEmailSettings());
    return getJson(`/users/${userId}/emailsettings.json`, state)()
      .then(json => dispatch(finishFetchEmailSettings('data', json)))
      .catch(({ message }) => dispatch(finishFetchEmailSettings(new Error(message))));
  };

export const changeEmailSettings = (userId, settings) =>
  (dispatch, getState) => {
    const state = getState();
    if (state.emailSetting.isPutting) return Promise.resolve();
    dispatch(requestChangeEmailSettings());
    return putJson(`/users/${userId}/changeemailsettings.json`, state)(null, settings)
      .then(() => dispatch(finishChangeEmailSettings('data', settings)))
      .catch(({ message }) => dispatch(finishChangeEmailSettings(new Error(message))));
  };
