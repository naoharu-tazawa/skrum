import React from 'react';
import PropTypes from 'prop-types';
import { Provider } from 'react-redux';
import { Router } from 'react-router';
import ReduxToastr from 'react-redux-toastr';
import routes from '../../routes';
import DevTools from './DevTools';

const Root = ({ store, history }) => (
  <Provider store={store}>
    <div>
      <Router history={history} routes={routes} />
      <ReduxToastr
        timeOut={4000}
        newestOnTop={false}
        preventDuplicates
        position="top-center"
        transitionIn="fadeIn"
        transitionOut="fadeOut"
        progressBar
      />
      <DevTools />
    </div>
  </Provider>
);

Root.propTypes = {
  store: PropTypes.object.isRequired,  // eslint-disable-line react/forbid-prop-types
  history: PropTypes.object.isRequired,  // eslint-disable-line react/forbid-prop-types
};

export default Root;
