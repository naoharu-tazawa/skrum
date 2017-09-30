import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { entityPropTypes, getEntityTypeSubject, EntityType, getEntityTypeId } from '../util/EntityUtil';
import { routePropTypes, explodePath, replacePath } from '../util/RouteUtil';
import { imagePath, dummyImagePath } from '../util/ImageUtil';
import styles from './EntityLink.css';

class EntityLink extends Component {

  static propTypes = {
    companyId: PropTypes.number.isRequired,
    entity: entityPropTypes,
    title: PropTypes.string,
    editor: PropTypes.node,
    local: PropTypes.bool,
    route: routePropTypes,
    fluid: PropTypes.bool,
    avatarOnly: PropTypes.bool,
    avatarSize: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    avatarTick: PropTypes.number,
    className: PropTypes.string,
  };

  render() {
    const { companyId, entity = {}, title, editor, local, route, fluid, avatarOnly,
      avatarSize = fluid ? '100%' : '40px', avatarTick, className } = this.props;
    const { imageError } = this.state || {};
    const { id, name, type } = entity;
    const imgSrc = imageError ? dummyImagePath(type) :
      `${imagePath(type, companyId, id)}${avatarTick ? `?t=${avatarTick}` : ''}`;
    const avatarContent = (
      <div
        className={styles.avatar}
        title={name}
        style={{ width: avatarSize, height: avatarSize }}
      >
        {id && (
          <img
            src={imgSrc}
            alt={name}
            onError={() => this.setState({ imageError: true })}
            width={avatarSize}
            height={avatarSize}
          />)}
      </div>);
    const nameContent = (title || editor || name || !id) && (
      <div className={styles.name} title={name}>
        {title && <p className={styles.title}>{title}</p>}
        {editor || (!avatarOnly && name) || (!id && <span>➖</span>)}
      </div>);
    return (
      <div
        className={`
        ${styles.component}
        ${id && styles.hasEntity}
        ${nameContent && styles.hasName}
        ${editor && styles.hasEditor}
        ${fluid && styles.fluid}
        ${imageError && styles.imageError}
        ${className || ''}`}
      >
        {(local || !id) && <div className={styles.noLink}>{avatarContent}{nameContent}</div>}
        {!local && id && (
          <Link to={replacePath({ subject: type, id, ...route }, { basicOnly: !route })}>
            {avatarContent}
            {nameContent}
          </Link>)}
      </div>);
  }
}

const mapStateToProps = (state, { local, route, entity }) => {
  const { companyId } = state.auth || {};
  if (local !== undefined || route) return { companyId, local: local || !route };
  const { id: entityId, type } = entity || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject, id } = explodePath(pathname);
  return { companyId, local: getEntityTypeSubject(type) === subject && id === entityId };
};

export default connect(
  mapStateToProps,
)(EntityLink);

export { EntityType, getEntityTypeId };
