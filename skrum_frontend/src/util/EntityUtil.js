import PropTypes from 'prop-types';
import { toPairs, flatten, omitBy, isUndefined } from 'lodash';
import { groupTypePropType } from './GroupUtil';

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

export const EntityTypeProperName = {
  [EntityType.USER]: 'User',
  [EntityType.GROUP]: 'Group',
  [EntityType.COMPANY]: 'Company',
};

export const EntityTypePluralName = {
  [EntityType.USER]: 'users',
  [EntityType.GROUP]: 'groups',
  [EntityType.COMPANY]: 'companies',
};

export const entityTypePropType = PropTypes.oneOf(flatten(toPairs(EntityType)));

export const entityPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string,
  type: entityTypePropType.isRequired,
  roleLevel: PropTypes.number,
  groupType: groupTypePropType,
});

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
  const subject = `${prefix}${EntityTypeProperName[type]}`;
  const { [`${subject}Id`]: id, [`${subject}Name`]: name } = data;
  const { [`${subject}RoleLevel`]: roleLevel } = data;
  const { [`${prefix}GroupType`]: groupType } = data;
  return omitBy({ id, name, type, roleLevel, groupType }, isUndefined);
};

export const mapEntityOutbound = ({ type, id, name, roleLevel, groupType }, prefix) =>
  omitBy({
    [`${prefix}Type`]: type,
    [`${prefix}${EntityTypeProperName[type]}Id`]: id,
    [`${prefix}${EntityTypeProperName[type]}Name`]: name,
    [`${prefix}${EntityTypeProperName[type]}RoleLevel`]: roleLevel,
    [`${prefix}GroupType`]: groupType,
  }, isUndefined);

export const getEntityTypeSubject = type => EntityTypeName[type];
