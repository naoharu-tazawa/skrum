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
    componentClassName: PropTypes.string,
    entityClassName: PropTypes.string,
  };

  render() {
    const { entity, entityClassName = '', heading, subject,
      local = !this.props.route, route, aspectRoute,
      plain, avatarSize, componentClassName } = this.props;
    return (
      <div className={`${!plain && styles.component} ${componentClassName || ''}`}>
        {heading && <div className={styles.heading}>{heading}</div>}
        <div className={styles.subjectArea}>
          <EntityLink
            componentClassName={`${styles.entity} ${entityClassName}`}
            {...{ entity, local, route, avatarSize }}
          />
          {subject && aspectRoute && (
            <Link
              to={replacePath({ subject: entity.type, id: entity.id, ...route, ...aspectRoute })}
              className={styles.subject}
            >
              {subject}
            </Link>)}
          {subject && local && <div className={styles.subject}>{subject}</div>}
        </div>
      </div>);
  }
}
