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
    subject: PropTypes.oneOfType([PropTypes.string, PropTypes.element]),
    local: PropTypes.bool,
    plain: PropTypes.bool,
    avatarSize: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    componentClassName: PropTypes.string,
    entityClassName: PropTypes.string,
  };

  render() {
    const { entity, entityClassName = '', heading, subject, local = true, plain, avatarSize,
      componentClassName } = this.props;
    return (
      <div className={`${!plain && styles.component} ${componentClassName || ''}`}>
        {heading && <div className={styles.heading}>{heading}</div>}
        <div className={styles.subjectArea}>
          <EntityLink
            componentClassName={`${styles.entity} ${entityClassName}`}
            entity={entity}
            local={local}
            avatarSize={avatarSize}
          />
          {subject && <div className={styles.subject}>{subject}</div>}
        </div>
      </div>);
  }
}
