import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Route, IndexRoute } from 'react-router';
import App from './base/container/App';
import Authenticated from './auth/container/Authenticated';
import Anonymous from './auth/container/Anonymous';
import RootRedirect from './auth/container/RootRedirect';
import UserRouter from './project/UserRouter';
import GroupRouter from './project/GroupRouter';
import CompanyRouter from './project/CompanyRouter';

const topPage = '/user';
const loginPage = '/login';

const children = { children: PropTypes.oneOfType([
  PropTypes.element.isRequired,
  PropTypes.arrayOf(PropTypes.element.isRequired).isRequired,
]) };

// inject config
class AuthenticatedRegion extends Component {
  static propTypes = children;
  render() {
    return (<Authenticated login={loginPage}>
      {this.props.children}
    </Authenticated>);
  }
}

class AnonymousRegion extends Component {
  static propTypes = children;
  render() {
    return (<Anonymous top={topPage}>
      {this.props.children}
    </Anonymous>);
  }
}

const RedirectRoute = () => (<RootRedirect top={topPage} login={loginPage} />);

export default <Route path="/" component={App} >
  <Route path="" component={AuthenticatedRegion}>
    <Route path="/user" component={UserRouter} />
    <Route path="/user/:userId/:timeframeId/:tab" component={UserRouter} />
    <Route path="/group/:groupId/:timeframeId/:tab" component={GroupRouter} />
    <Route path="/company/:companyId/:timeframeId/:tab" component={CompanyRouter} />
  </Route>
  <Route path="/login" component={AnonymousRegion} />
  <IndexRoute component={RedirectRoute} />
  <Route path="*" component={RedirectRoute} />
</Route>;
