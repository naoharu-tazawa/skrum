import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { tabPropType } from '../navigation/header/propTypes';
import OKRContainer from './OKR/OKRContainer';
import styles from './GroupRouter.css';

export default class UserRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      userId: PropTypes.string.isRequired,
      timeframeId: PropTypes.string.isRequired,
      tab: tabPropType.isRequired,
    }),
  };

  renderContent() {
    switch (this.props.params.tab) {
      case 'objective':
        return <OKRContainer subject="user" />;
      case 'control':
        return <OKRContainer subject="user" />;
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
