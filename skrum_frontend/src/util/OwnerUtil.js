import { mapEntity, mapEntityOutbound, EntityTypeName } from './EntityUtil';

export const mapOwner = data => mapEntity(data, 'owner');

export const mapOwnerOutbound = data => mapEntityOutbound(data, 'owner');

export const getOwnerTypeSubject = type => EntityTypeName[type];
