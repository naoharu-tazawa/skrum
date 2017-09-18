import PropTypes from 'prop-types';
import { toPairs, flatten, capitalize, omitBy, isUndefined } from 'lodash';

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

export const entityTypePropType = PropTypes.oneOf(flatten(toPairs(EntityType)));

export const getEntityTypeId = (subject) => {
  switch (subject) {
    case 'user': return EntityType.USER;
    case 'group': return EntityType.GROUP;
    case 'company': return EntityType.COMPANY;
    default: return undefined;
  }
};

export const mapEntity = (data, prefix) => {
  const { [`${prefix}Type`]: type } = data;
  const subject = `${prefix}${capitalize(EntityTypeName[type])}`;
  const { [`${subject}Id`]: id, [`${subject}Name`]: name } = data;
  const { [`${subject}RoleLevel`]: roleLevel } = data;
  const { [`${prefix}GroupType`]: groupType } = data;
  return omitBy({ id, name, type, roleLevel, groupType }, isUndefined);
};

export const mapEntityOutbound = ({ type, id, name }, prefix) =>
  omitBy({
    [`${prefix}Type`]: type,
    [`${prefix}${capitalize(EntityTypeName[type])}Id`]: id,
    [`${prefix}${capitalize(EntityTypeName[type])}Name`]: name,
  }, isUndefined);

export const getEntityTypeSubject = type => EntityTypeName[type];
