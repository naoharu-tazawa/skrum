import React from 'react';
import { Route, IndexRedirect } from 'react-router';
import App from './base/container/App';
import UserOnly from './auth/container/UserOnly';
import Anonymous from './auth/container/Anonymous';
import Login from './auth/container/Login';
import TeamRouter from './project/TeamRouter';
import { NotFoundContainer } from './error/index';

export default <Route path="/" component={App} >
  <IndexRedirect to="/team/piyo/okr" />
  <Route path="/" component={UserOnly}>
    <Route path="/team/:teamName/:subMenu" component={TeamRouter} />
  </Route>
  <Route path="" component={Anonymous}>
    <Route path="" component={Login} />
    <Route path="/login" component={Login} />
  </Route>
  <Route path="*" component={NotFoundContainer} />
</Route>;
