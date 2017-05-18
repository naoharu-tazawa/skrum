import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tabPropType } from '../navigation/header/propTypes';
import OKRContainer from './OKR/OKRContainer';
import MapContainer from './Map/MapContainer';
import styles from './GroupRouter.css';

export default class CompanyRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      companyId: PropTypes.string.isRequired,
      timeframeId: PropTypes.string.isRequired,
      tab: tabPropType.isRequired,
    }),
  };

  renderContent() {
    switch (this.props.params.tab) {
      case 'objective':
        return <OKRContainer subject="company" />;
      case 'map':
        return <MapContainer subject="company" />;
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
