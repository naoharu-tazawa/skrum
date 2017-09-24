import React, { Component } from 'react';
import PropTypes from 'prop-types';
import OKRContainer from './OKR/OKRContainer';
import MapContainer from './Map/MapContainer';
import TimelineContainer from './Timeline/TimelineContainer';
import GroupManagementContainer from './GroupManagement/GroupManagementContainer';
import { tabPropType, explodeTab } from '../util/RouteUtil';
import styles from './GroupRouter.css';

export default class UserRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      userId: PropTypes.string,
      timeframeId: PropTypes.string,
      tab: tabPropType,
      okrId: PropTypes.string,
    }),
  };

  renderContent() {
    const { tab, userId } = this.props.params;
    switch (explodeTab(tab)) {
      case 'objective':
        return <OKRContainer subject="user" />;
      case 'map':
        return <MapContainer subject="user" />;
      case 'timeline':
        return <TimelineContainer subject="user" id={userId} />;
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
