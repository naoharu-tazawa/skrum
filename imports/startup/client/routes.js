import React from 'react'
import { mount } from 'react-mounter'
import { FlowRouter } from 'meteor/kadira:flow-router'
import { Accounts } from 'meteor/std:accounts-ui';
import Perf from 'react-addons-perf'
import MainLayout from '../../common-ui/layouts/MainLayout'
import Home       from '../../common-ui/pages/Home'
import Dashboards from '../../common-ui/pages/Dashboards'
import Profiles   from '../../common-ui/pages/Profiles'
import Maps       from '../../common-ui/pages/Maps'
import Explore    from '../../common-ui/pages/Explore'

if (process.env.NODE_ENV === 'development') {
  window.Perf = Perf
}

Accounts.ui.config({
  passwordSignupFields: 'USERNAME_AND_EMAIL',
  loginPath: '/login',
  minimumPasswordLength: 6,
  onSignedInHook: () => FlowRouter.go('/'),
  onSignedOutHook: () => FlowRouter.go('/')
})

FlowRouter.route('/', {
  name: 'home',
  action() {
    mount(MainLayout, { content: () => Meteor.user() ? <Profiles /> : <Home /> })
  },
})

FlowRouter.route('/dashboards', {
  action() {
    mount(MainLayout, { content: () => <Dashboards /> })
  },
})

FlowRouter.route('/profiles', {
  action() {
    mount(MainLayout, { content: () => <Profiles /> })
  },
})

FlowRouter.route('/maps', {
  action() {
    mount(MainLayout, { content: () => <Maps /> })
  },
})

FlowRouter.route('/explore', {
  action() {
    mount(MainLayout, { content: () => <Explore /> })
  },
})

FlowRouter.route("/signin", {
  action() {
    mount(MainLayout, { content: () => <Accounts.ui.LoginForm /> })
  }
})
