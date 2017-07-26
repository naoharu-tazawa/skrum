import React, { Component } from 'react';
import PropTypes from 'prop-types';
import EntityLink from './EntityLink';
import styles from './OwnerSubject.css';

export const ownerPropType = PropTypes.shape({
  id: PropTypes.number,
  name: PropTypes.string,
  type: PropTypes.string,
});

export default class OwnerSubject extends Component {

  static propTypes = {
    owner: ownerPropType,
    heading: PropTypes.string,
    subject: PropTypes.string.isRequired,
  };

  render() {
    const { owner, heading, subject } = this.props;
    return (
      <div className={styles.component}>
        {heading && <div className={styles.heading}>{heading}</div>}
        <div className={styles.subjectArea}>
          <EntityLink componentClassName={styles.owner_info} entity={owner} local />
          {subject && <div className={styles.subject}>{subject}</div>}
        </div>
      </div>);
  }
}
