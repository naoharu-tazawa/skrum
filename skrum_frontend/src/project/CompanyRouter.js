import React, { Component } from 'react';
import PropTypes from 'prop-types';
import OKRContainer from './OKR/OKRContainer';
import MapContainer from './Map/MapContainer';
import TimelineContainer from './Timeline/TimelineContainer';
import { tabPropType, explodeTab } from '../util/RouteUtil';
import styles from './GroupRouter.css';

export default class CompanyRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      companyId: PropTypes.string.isRequired,
      timeframeId: PropTypes.string,
      tab: tabPropType.isRequired,
      okrId: PropTypes.string,
    }),
  };

  renderContent() {
    const { tab, companyId } = this.props.params;
    switch (explodeTab(tab)) {
      case 'objective':
        return <OKRContainer subject="company" />;
      case 'map':
        return <MapContainer subject="company" />;
      case 'timeline':
        return <TimelineContainer subject="company" id={companyId} />;
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
