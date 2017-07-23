import React, { Component } from 'react';
import PropTypes from 'prop-types';
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
          <div className={styles.owner_info}>
            <div className={styles.avatar}>
              <img src="/img/common/icn_user.png" alt="User" />
            </div>
            <div className={styles.info}>
              <p className={styles.owner_name}>{owner.name}</p>
            </div>
          </div>
          {subject && <div className={styles.subject}>{subject}</div>}
        </div>
      </div>);
  }
}
