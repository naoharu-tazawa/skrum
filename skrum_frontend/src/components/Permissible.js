import { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { find, isFunction } from 'lodash';
import { entityTypePropType, EntityType } from '../util/EntityUtil';
import { groupTypePropType, GroupType } from '../util/GroupUtil';
import { isSuperRole, isAdminRole, isAuthoritativeOver } from '../util/UserUtil';

export const entityPropType = PropTypes.shape({
  id: PropTypes.number.isRequired,
  type: entityTypePropType.isRequired,
  roleLevel: PropTypes.number,
  groupType: groupTypePropType,
});

class Permissible extends PureComponent {

  static propTypes = {
    entity: entityPropType.isRequired,
    permitted: PropTypes.bool.isRequired,
    fallback: PropTypes.element,
    children: PropTypes.oneOfType([PropTypes.node, PropTypes.func]).isRequired,
  };

  render() {
    const { children, fallback = null, permitted } = this.props;
    if (isFunction(children)) {
      return children({ permitted, fallback });
    }
    return permitted ? children : fallback;
  }
}

const mapStateToProps = (state, { entity: { type, id, roleLevel, groupType } }) => {
  const { userId: currentUserId, roleLevel: currentRoleLevel } = state.auth || {};
  let permitted = false;
  if (isSuperRole(currentRoleLevel)) {
    permitted = true;
  } else if (isAdminRole(currentRoleLevel) && type === EntityType.USER &&
    isAuthoritativeOver(currentRoleLevel, roleLevel)) {
    permitted = true;
  } else if (isAdminRole(currentRoleLevel) && type !== EntityType.USER) {
    permitted = true;
  } else if (type === EntityType.GROUP) {
    const { teams = [] } = state.top.data || {};
    permitted = groupType === GroupType.TEAM || !!find(teams, { groupId: id });
  } else if (type === EntityType.USER) {
    permitted = id === currentUserId;
  }
  return { permitted };
};

export default connect(
  mapStateToProps,
)(Permissible);
