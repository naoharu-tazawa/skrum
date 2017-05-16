import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tabPropType } from '../navigation/header/propTypes';
import OKRContainer from './OKR/OKRContainer';
import GroupManagementContainer from './GroupManagement/GroupManagementContainer';
import styles from './GroupRouter.css';

export default class UserRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      userId: PropTypes.string,
      timeframeId: PropTypes.string,
      tab: tabPropType,
    }),
  };

  renderContent() {
    switch (this.props.params.tab) {
      case 'objective':
        return <OKRContainer subject="user" />;
      case 'control':
        return <GroupManagementContainer subject="user" />;
      default:
        return null;
    }
  }

  render() {
    return (
      <div className={styles.container}>
        {this.renderContent()}
      </div>);
  }
}
