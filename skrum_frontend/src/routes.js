import React from 'react';
import { Route } from 'react-router';
import App from './base/container/App';
import UserOnly from './auth/container/UserOnly';
import Anonymous from './auth/container/Anonymous';
import Login from './auth/container/Login';
import Sample from './sample/SampleContainer';
import { NotFoundContainer } from './error/index';

export default <Route path="/" component={App} >
  <Route component={UserOnly}>
    <Route path="/piyo" component={Sample} />
  </Route>
  <Route path="" component={Anonymous}>
    <Route path="" component={Login} />
    <Route path="/login" component={Login} />
  </Route>
  <Route path="*" component={NotFoundContainer} />
</Route>;
