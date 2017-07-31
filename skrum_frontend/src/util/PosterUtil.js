import { mapEntity, mapEntityOutbound, EntityTypeName } from './EntityUtil';

export const mapPoster = data => mapEntity(data, 'poster');

export const mapPosterOutbound = data => mapEntityOutbound(data, 'poster');

export const getPosterTypeSubject = type => EntityTypeName[type];
