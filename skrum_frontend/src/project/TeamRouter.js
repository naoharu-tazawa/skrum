import React, { Component } from 'react';
import PropTypes from 'prop-types';
import OKRContainer from './OKR/OKRContainer';
import styles from './TeamRouter.css';

export default class TeamRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      teamId: PropTypes.string,
      action: PropTypes.string,
    }),
  };

  renderContent() {
    switch (this.props.params.action) {
      case 'okr':
        return <OKRContainer />;
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
