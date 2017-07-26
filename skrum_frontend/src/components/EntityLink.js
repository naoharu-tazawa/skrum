import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { entityTypePropType } from '../util/EntityUtil';
import { getOwnerTypeSubject } from '../util/OwnerUtil';
import { explodePath, replacePath } from '../util/RouteUtil';
import styles from './EntityLink.css';

export const entityPropType = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: entityTypePropType.isRequired,
});

class EntityLink extends Component {

  static propTypes = {
    entity: entityPropType.isRequired,
    title: PropTypes.string,
    editor: PropTypes.node,
    local: PropTypes.bool,
    componentClassName: PropTypes.string,
  };

  render() {
    const { entity, title, editor, local, componentClassName } = this.props;
    const { id, name, type } = entity;
    const avatarContent = (
      <div className={styles.avatar}>
        <img src="/img/common/icn_user.png" alt="Owner" />
      </div>);
    const nameContent = (
      <div className={styles.name}>
        {title && <p className={styles.title}>{title}</p>}
        {editor || name}
      </div>);
    return (
      <div className={`${styles.component} ${componentClassName || ''}`}>
        {local && avatarContent}
        {local && nameContent}
        {!local && <Link to={replacePath({ subject: type, id }, { basicOnly: true })}>
          {avatarContent}
          {nameContent}
        </Link>}
      </div>);
  }
}

const mapStateToProps = (state, props) => {
  const { local, entity } = props;
  if (local) return { local };
  const { id: entityId, type } = entity;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject, id } = explodePath(pathname);
  return { local: getOwnerTypeSubject(type) === subject && id === entityId };
};

export default connect(
  mapStateToProps,
)(EntityLink);
