import { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { find } from 'lodash';
import { entityTypePropType, EntityType } from '../util/EntityUtil';
import { isAdminRoleAndAbove } from '../util/UserUtil';

export const entityPropType = PropTypes.shape({
  id: PropTypes.number.isRequired,
  type: entityTypePropType.isRequired,
});

class Editable extends PureComponent {

  static propTypes = {
    entity: entityPropType.isRequired,
    children: PropTypes.element.isRequired,
    fallback: PropTypes.element.isRequired,
    granted: PropTypes.bool.isRequired,
  };

  render() {
    const { children, fallback, granted } = this.props;
    return granted ? children : fallback;
  }
}

const mapStateToProps = (state, { entity: { type, id } }) => {
  const { userId: currentUserId, roleLevel: currentRoleLevel } = state.auth || {};
  let granted = false;
  if (isAdminRoleAndAbove(currentRoleLevel)) {
    granted = true;
  } else if (type === EntityType.GROUP) {
    const { teams = [] } = state.top.data || {};
    granted = !!find(teams, { groupId: id });
  } else if (type === EntityType.USER) {
    granted = id === currentUserId;
  }
  return { granted };
};

export default connect(
  mapStateToProps,
)(Editable);
