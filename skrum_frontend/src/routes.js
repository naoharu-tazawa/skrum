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
import UserSettingContainer from './project/UserSetting/UserSettingContainer';
import GroupSettingContainer from './project/GroupSetting/GroupSettingContainer';
import CompanyProfileContainer from './project/CompanyProfile/CompanyProfileContainer';
import TimeframeContainer from './project/Timeframe/TimeframeContainer';
import PasswordChangeContainer from './project/PasswordChange/PasswordChangeContainer';

const topPage = '/o/u';
const loginPage = '/login';

const children = {
  children: PropTypes.oneOfType([
    PropTypes.element.isRequired,
    PropTypes.arrayOf(PropTypes.element.isRequired).isRequired,
  ]),
};

// inject config
class AuthenticatedRegion extends Component {
  static propTypes = children;

  render() {
    return (
      <Authenticated top={topPage} login={loginPage}>
        {this.props.children}
      </Authenticated>);
  }
}

class AnonymousRegion extends Component {
  static propTypes = children;

  render() {
    return (
      <Anonymous top={topPage}>
        {this.props.children}
      </Anonymous>);
  }
}

const RedirectRoute = () => <RootRedirect top={topPage} login={loginPage} />;

export default (
  <Route path="/" component={App} >
    <Route path="" component={AuthenticatedRegion}>
      <Route path={topPage} component={UserRouter} />
      <Route path="/:tab/u/:userId/:timeframeId" component={UserRouter} />
      <Route path="/:tab/u/:userId/:timeframeId/o/:okrId" component={UserRouter} />
      <Route path="/:tab/g/:groupId/:timeframeId" component={GroupRouter} />
      <Route path="/:tab/g/:groupId/:timeframeId/o/:okrId" component={GroupRouter} />
      <Route path="/:tab/c/:companyId/:timeframeId" component={CompanyRouter} />
      <Route path="/:tab/c/:companyId/:timeframeId/o/:okrId" component={CompanyRouter} />
      <Route path="/s/u" component={UserSettingContainer} />
      <Route path="/s/g" component={GroupSettingContainer} />
      <Route path="/s/c" component={CompanyProfileContainer} />
      <Route path="/s/t" component={TimeframeContainer} />
      <Route path="/s/a" component={PasswordChangeContainer} />
    </Route>
    <Route path="/login" component={AnonymousRegion} />
    <IndexRoute component={RedirectRoute} />
    <Route path="*" component={RedirectRoute} />
  </Route>);
