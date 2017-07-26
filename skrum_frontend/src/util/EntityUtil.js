import PropTypes from 'prop-types';

export const EntityType = {
  USER: '1',
  GROUP: '2',
  COMPANY: '3',
};

export const EntityTypeName = {
  [EntityType.USER]: 'user',
  [EntityType.GROUP]: 'group',
  [EntityType.COMPANY]: 'company',
};

export const entityTypePropType =
  PropTypes.oneOf([EntityType.USER, EntityType.GROUP, EntityType.COMPANY]);
