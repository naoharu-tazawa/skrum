import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { entityTypePropType, getEntityTypeSubject, EntityType, getEntityTypeId } from '../util/EntityUtil';
import { explodePath, replacePath, implodeSubject } from '../util/RouteUtil';
import styles from './EntityLink.css';

export const entityPropType = PropTypes.shape({
  id: PropTypes.number,
  name: PropTypes.string,
  type: entityTypePropType.isRequired,
});

class EntityLink extends Component {

  static propTypes = {
    companyId: PropTypes.number.isRequired,
    entity: entityPropType,
    title: PropTypes.string,
    editor: PropTypes.node,
    local: PropTypes.bool,
    fluid: PropTypes.bool,
    avatarOnly: PropTypes.bool,
    avatarSize: PropTypes.string,
    componentClassName: PropTypes.string,
  };

  render() {
    const { companyId, entity = {}, title, editor, local, fluid, avatarOnly, avatarSize = '40px',
      componentClassName } = this.props;
    const { imageError } = this.state || {};
    const { id, name, type } = entity;
    const skrumBucket = 'https://s3-ap-northeast-1.amazonaws.com/skrum';
    const imgSrc = imageError ? `/img/dummy_${getEntityTypeSubject(type)}.svg` :
      `${skrumBucket}/c/${companyId}/${implodeSubject(type)}/${id}/picture`;
    const avatarContent = (
      <div className={styles.avatar} title={name}>
        {id && (
          <img
            src={imgSrc}
            alt={name}
            onError={() => this.setState({ imageError: true })}
            {...{ width: avatarSize, height: avatarSize }}
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
        ${componentClassName || ''}`}
      >
        {(local || !id) && avatarContent}
        {(local || !id) && nameContent}
        {!local && id && <Link to={replacePath({ subject: type, id }, { basicOnly: true })}>
          {avatarContent}
          {nameContent}
        </Link>}
      </div>);
  }
}

const mapStateToProps = (state, { local, entity }) => {
  const { companyId } = state.auth || {};
  if (local !== undefined) return { companyId, local };
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
