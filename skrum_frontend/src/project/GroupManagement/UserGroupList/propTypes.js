import PropTypes from 'prop-types';
import { groupTypePropType } from '../../../util/GroupUtil';

export const userGroupPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: groupTypePropType.isRequired,
  achievementRate: PropTypes.number.isRequired,
});

export const userGroupsPropTypes = PropTypes.arrayOf(userGroupPropTypes);
