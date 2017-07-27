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
    subject: PropTypes.string,
    componentClassName: PropTypes.string,
  };

  render() {
    const { owner, heading, subject, componentClassName } = this.props;
    return (
      <div className={`${styles.component} ${componentClassName || ''}`}>
        {heading && <div className={styles.heading}>{heading}</div>}
        <div className={styles.subjectArea}>
          <EntityLink componentClassName={styles.owner_info} entity={owner} local />
          {subject && <div className={styles.subject}>{subject}</div>}
        </div>
      </div>);
  }
}
