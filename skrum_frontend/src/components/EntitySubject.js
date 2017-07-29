import React, { Component } from 'react';
import PropTypes from 'prop-types';
import EntityLink from './EntityLink';
import styles from './EntitySubject.css';

export const entityPropType = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: PropTypes.string.isRequired,
});

export default class EntitySubject extends Component {

  static propTypes = {
    entity: entityPropType,
    heading: PropTypes.string,
    subject: PropTypes.string,
    componentClassName: PropTypes.string,
  };

  render() {
    const { entity, heading, subject, componentClassName } = this.props;
    return (
      <div className={`${styles.component} ${componentClassName || ''}`}>
        {heading && <div className={styles.heading}>{heading}</div>}
        <div className={styles.subjectArea}>
          <EntityLink componentClassName={styles.entity} entity={entity} local />
          {subject && <div className={styles.subject}>{subject}</div>}
        </div>
      </div>);
  }
}
