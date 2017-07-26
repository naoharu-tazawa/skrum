import { capitalize } from 'lodash';
import { EntityType, EntityTypeName } from './EntityUtil';

export const getOwnerTypeId = (ownerType) => {
  switch (ownerType) {
    case 'user': return EntityType.USER;
    case 'group': return EntityType.GROUP;
    case 'company': return EntityType.COMPANY;
    default: return undefined;
  }
};

export const getOwnerTypeSubject = ownerType => EntityTypeName[ownerType];

export const mapOwner = (data) => {
  const { ownerType } = data;
  const ownerSubject = `owner${capitalize(getOwnerTypeSubject(ownerType))}`;
  const { [`${ownerSubject}Id`]: ownerId, [`${ownerSubject}Name`]: ownerName } = data;
  return { id: ownerId, name: ownerName, type: ownerType };
};

export const mapOwnerOutbound = ({ type, id, name }) => ({
  ownerType: type,
  [`owner${capitalize(getOwnerTypeSubject(type))}Id`]: id,
  [`owner${capitalize(getOwnerTypeSubject(type))}Name`]: name,
});
