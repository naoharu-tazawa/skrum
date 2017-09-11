import PropTypes from 'prop-types';
import { capitalize, omitBy, isUndefined } from 'lodash';

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
  return omitBy({ id, name, type }, isUndefined);
};

export const mapEntityOutbound = ({ type, id, name }, prefix) =>
  omitBy({
    [`${prefix}Type`]: type,
    [`${prefix}${capitalize(EntityTypeName[type])}Id`]: id,
    [`${prefix}${capitalize(EntityTypeName[type])}Name`]: name,
  }, isUndefined);

export const getEntityTypeSubject = type => EntityTypeName[type];
