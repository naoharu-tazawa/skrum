import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tabPropType } from '../navigation/header/propTypes';
import OKRContainer from './OKR/OKRContainer';
import GroupManagementContainer from './GroupManagement/GroupManagementContainer';
import styles from './GroupRouter.css';

export default class GroupRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      groupId: PropTypes.string.isRequired,
      timeframeId: PropTypes.string.isRequired,
      tab: tabPropType.isRequired,
    }),
  };

  renderContent() {
    switch (this.props.params.tab) {
      case 'objective':
        return <OKRContainer subject="group" />;
      case 'control':
        return <GroupManagementContainer subject="group" />;
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
