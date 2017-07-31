import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { entityTypePropType, EntityType } from '../util/EntityUtil';
import { getOwnerTypeSubject } from '../util/OwnerUtil';
import { explodePath, replacePath } from '../util/RouteUtil';
import styles from './EntityLink.css';

export const entityPropType = PropTypes.shape({
  id: PropTypes.number,
  name: PropTypes.string,
  type: entityTypePropType.isRequired,
});

class EntityLink extends Component {

  static propTypes = {
    entity: entityPropType,
    title: PropTypes.string,
    editor: PropTypes.node,
    local: PropTypes.bool,
    componentClassName: PropTypes.string,
  };

  render() {
    const { entity = {}, title, editor, local, componentClassName } = this.props;
    const { id, name, type } = entity;
    const avatarContent = (
      <div className={styles.avatar} title={name}>
        {id && <img src="/img/common/icn_user.png" alt="Owner" />}
      </div>);
    const nameContent = (title || editor || name || !id) && (
      <div className={styles.name} title={name}>
        {title && <p className={styles.title}>{title}</p>}
        {editor || name || (!id && <span>➖</span>)}
      </div>);
    return (
      <div className={`${styles.component} ${id && styles.hasEntity} ${componentClassName || ''}`}>
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
  if (local !== undefined) return { local };
  const { id: entityId, type } = entity || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject, id } = explodePath(pathname);
  return { local: getOwnerTypeSubject(type) === subject && id === entityId };
};

export default connect(
  mapStateToProps,
)(EntityLink);

export { EntityType };
