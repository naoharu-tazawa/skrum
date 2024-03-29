import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import EntityLink from './EntityLink';
import { entityPropTypes } from '../util/EntityUtil';
import { routePropTypes, replacePath } from '../util/RouteUtil';
import styles from './EntitySubject.css';

export default class EntitySubject extends Component {

  static propTypes = {
    entity: entityPropTypes,
    heading: PropTypes.string,
    subject: PropTypes.oneOfType([PropTypes.string, PropTypes.element]),
    local: PropTypes.bool,
    route: routePropTypes,
    aspectRoute: routePropTypes,
    plain: PropTypes.bool,
    avatarSize: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    className: PropTypes.string,
    entityClassName: PropTypes.string,
  };

  render() {
    const { entity, entityClassName, heading, subject,
      local = !this.props.route, route, aspectRoute,
      plain, avatarSize, className } = this.props;
    return (
      <div className={`${!plain && styles.component} ${className || ''}`}>
        {heading && <div className={styles.heading}>{heading}</div>}
        <div className={styles.subjectArea}>
          <EntityLink
            className={`${styles.entity} ${entityClassName || ''}`}
            {...{ entity, local, route, avatarSize }}
          />
          {subject && aspectRoute && (
            <Link
              to={replacePath({ subject: entity.type, id: entity.id, ...route, ...aspectRoute })}
              className={`${styles.subject} ${styles.subjectLink}`}
            >
              <span className={styles.subjectText}>{subject}</span>
            </Link>)}
          {subject && local && <div className={styles.subject}>
            <span className={styles.subjectText}>{subject}</span>
          </div>}
        </div>
      </div>);
  }
}
