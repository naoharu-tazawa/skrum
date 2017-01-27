import React, { Component, PropTypes } from 'react';
import { createContainer } from 'meteor/react-meteor-data';
import { Accounts } from 'meteor/std:accounts-ui';
// import { Provider } from 'react-redux'
// import store from '/imports/redux/store'

class MainLayout extends Component {
  // return <Provider store={store}>
  //   {content()}
  // </Provider>
  render() {
    return <div id="container">
      <section id="menu">
        <h1 id="appname"><a href="/">sKRum</a></h1>
        <ul className="nav navbar-nav">
          { this.props.currentUser ? <li className="menuitem"><a href="/dashboards">Dashboards</a></li> : '' }
          { this.props.currentUser ? <li className="menuitem"><a href="/profiles">Profiles</a></li> : '' }
          { this.props.currentUser ? <li className="menuitem"><a href="/maps">Maps</a></li> : '' }
          { this.props.currentUser ? <li className="menuitem"><a href="/explore">Explore</a></li> : '' }
          { this.props.currentUser ? '' : <li className="menuitem"><a href="/signin">Sign In</a></li> }
          { this.props.currentUser ? <li className="menuitem right-nav"><a href="/signin">{this.props.currentUser.username}</a></li> : '' }
        </ul>
      </section>
      <div id="content-container">
        {this.props.content()}
      </div>
    </div>
  }
}

MainLayout.propTypes = {
  content: PropTypes.func.isRequired,
  currentUser: PropTypes.object,
}

export default createContainer((params) => {
  //Meteor.subscribe('tasks')
  return {
    content: params.content,
    currentUser: Meteor.user(),
  }
}, MainLayout)
