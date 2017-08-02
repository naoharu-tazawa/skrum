import { mapEntity, mapEntityOutbound } from './EntityUtil';

export const mapOwner = data => mapEntity(data, 'owner');

export const mapOwnerOutbound = data => mapEntityOutbound(data, 'owner');
