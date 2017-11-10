import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { isEmpty } from 'lodash';
import { entityPropTypes, getEntityTypeSubject, EntityType, EntityTypePluralName, getEntityTypeId } from '../util/EntityUtil';
import { routePropTypes, explodePath, replacePath } from '../util/RouteUtil';
import { imagePath, dummyImagePath } from '../util/ImageUtil';
import styles from './EntityLink.css';

class EntityLink extends Component {

  static propTypes = {
    companyId: PropTypes.number.isRequired,
    entity: entityPropTypes,
    version: PropTypes.number,
    title: PropTypes.string,
    editor: PropTypes.node,
    local: PropTypes.bool,
    route: routePropTypes,
    basicRoute: PropTypes.bool,
    fluid: PropTypes.bool,
    avatarOnly: PropTypes.bool,
    avatarSize: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    className: PropTypes.string,
  };

  render() {
    const { companyId, entity = {}, version, title, editor, local, route, basicRoute, fluid,
      avatarOnly, avatarSize = fluid ? '100%' : '40px', className } = this.props;
    const { imageError } = this.state || {};
    const { id, name, type } = entity;
    const imgSrc = version !== 0 && !imageError && imagePath(type, companyId, id, version);
    const avatarContent = (
      <div
        className={styles.avatar}
        title={name}
        style={{
          ...{ minWidth: avatarSize, minHeight: avatarSize },
          ...{ maxWidth: avatarSize, maxHeight: avatarSize },
        }}
      >
        {id && (
          <img
            className={!imgSrc && styles.dummyImage}
            src={imgSrc || dummyImagePath(type)}
            alt={name}
            onError={() => this.setState({ imageError: true })}
            width={avatarSize}
            height={avatarSize}
          />)}
      </div>);
    const nameContent = !avatarOnly && (title || editor || name || !id) && (
      <div className={styles.name} title={name}>
        {title && <p className={styles.title}>{title}</p>}
        {editor || name || (!id && <span>➖</span>)}
      </div>);
    const basicOnly = !route || basicRoute;
    return (
      <div
        className={`
        ${styles.component}
        ${id && styles.hasEntity}
        ${nameContent && styles.hasName}
        ${editor && styles.hasEditor}
        ${fluid && styles.fluid}
        ${className || ''}`}
      >
        {(local || !id) && <div className={styles.noLink}>{avatarContent}{nameContent}</div>}
        {!local && id && (
          <Link to={replacePath({ subject: type, id, ...route }, { basicOnly })}>
            {avatarContent}
            {nameContent}
          </Link>)}
      </div>);
  }
}

const mapStateToProps = (state, { local, route, entity }) => {
  const { companyId } = state.auth;
  const { images } = state.base;
  const { type, id } = entity || {};
  const version = isEmpty(images) ? 0 : type && images[EntityTypePluralName[type]][id];
  if (local !== undefined || route) return { companyId, local: local || !route, version };
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject, id: subjectId } = explodePath(pathname);
  const defaultLocal = getEntityTypeSubject(type) === subject && subjectId === id;
  return { companyId, local: defaultLocal, version };
};

export default connect(
  mapStateToProps,
)(EntityLink);

export { EntityType, getEntityTypeId };
