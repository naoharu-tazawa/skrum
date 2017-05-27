import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tabPropType } from '../navigation/header/propTypes';
import OKRContainer from './OKR/OKRContainer';
import MapContainer from './Map/MapContainer';
import TimelineContainer from './Timeline/TimelineContainer';
import GroupManagementContainer from './GroupManagement/GroupManagementContainer';
import OKRDetailsContainer from './OKRDetails/OKRDetailsContainer';
import styles from './GroupRouter.css';

export default class GroupRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      groupId: PropTypes.string.isRequired,
      timeframeId: PropTypes.string.isRequired,
      tab: tabPropType.isRequired,
      okrId: PropTypes.string,
    }),
  };

  renderContent() {
    const { tab, okrId } = this.props.params;
    if (okrId) {
      return <OKRDetailsContainer />;
    }
    switch (tab) {
      case 'objective':
        return <OKRContainer subject="group" />;
      case 'map':
        return <MapContainer subject="group" />;
      case 'timeline':
        return <TimelineContainer />;
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
