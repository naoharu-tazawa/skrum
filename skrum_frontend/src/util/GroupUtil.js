import PropTypes from 'prop-types';
import { toPairs, flatten } from 'lodash';

export const GroupType = {
  DEPARTMENT: '1',
  TEAM: '2',
};

export const GroupTypeName = {
  [GroupType.DEPARTMENT]: '部署',
  [GroupType.TEAM]: 'チーム',
};

export const groupTypePropType = PropTypes.oneOf(flatten(toPairs(GroupType)));
