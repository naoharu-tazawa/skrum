import readBlob from 'read-blob';
import dataUrlParser from 'parse-data-url';
import config from '../config/config';
import { implodeSubject } from './RouteUtil';
import { getEntityTypeSubject } from './EntityUtil';

const { bucket } = config;

export const imagePath = (entityType, companyId, id, version = 1) => `
  ${bucket}
  /c
  /${companyId}
  ${implodeSubject(entityType) === 'c' ? '' : `/${implodeSubject(entityType)}/${id}`}
  /image${version}
  `.replace(/[\s\n]/g, '');

export const dummyImagePath = entityType =>
  `/img/dummy_${getEntityTypeSubject(entityType)}.png`;

export const dummyImagePathForD3 = entityType =>
  `/img/dummy_${getEntityTypeSubject(entityType)}_for_D3.png`;

export const loadImageDataUrl = ({ preview }) =>
  fetch(preview)
    .then(res => res.blob()
      .then(blob => readBlob.dataurl(blob)));

export const parseDataUrl = (dataUrl) => {
  const { data: image, mediaType: mimeType } = dataUrlParser(dataUrl);
  return { image, mimeType };
};

export const loadImage = ({ preview }) =>
  loadImageDataUrl({ preview }).then(parseDataUrl);
