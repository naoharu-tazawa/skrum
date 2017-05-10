import React from 'react';
import PropTypes from 'prop-types';
import { Provider } from 'react-redux';
import { Router } from 'react-router';
import routes from '../../routes';

const Root = ({ store, history }) => (
  <Provider store={store}>
    <Router history={history} routes={routes} />
  </Provider>
);

Root.propTypes = {
  store: PropTypes.object.isRequired,  // eslint-disable-line react/forbid-prop-types
  history: PropTypes.object.isRequired,  // eslint-disable-line react/forbid-prop-types
};

export default Root;
