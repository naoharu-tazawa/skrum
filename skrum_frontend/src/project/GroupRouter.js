import React, { Component } from 'react';
import PropTypes from 'prop-types';
import OKRContainer from './OKR/OKRContainer';
import styles from './GroupRouter.css';

export default class GroupRouter extends Component {
  static propTypes = {
    params: PropTypes.shape({
      groupId: PropTypes.string,
      action: PropTypes.string,
    }),
  };

  renderContent() {
    switch (this.props.params.action) {
      case 'objective':
        return <OKRContainer subject="group" />;
      case 'control':
        return <OKRContainer subject="group" />;
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
