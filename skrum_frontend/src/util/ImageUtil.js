import readBlob from 'read-blob';
import parseDataUrl from 'parse-data-url';
import config from '../config/config';
import { implodeSubject } from './RouteUtil';
import { getEntityTypeSubject } from './EntityUtil';

const { bucket } = config;

export const imagePath = (entityType, companyId, id) => `
  ${bucket}
  /c
  /${companyId}
  ${implodeSubject(entityType) === 'c' ? '' : `/${implodeSubject(entityType)}/${id}`}
  /image1
  `.replace(/[\s\n]/g, '');

export const dummyImagePath = entityType =>
  `/img/dummy_${getEntityTypeSubject(entityType)}.svg`;

export const dummyImagePathForD3 = entityType =>
  `/img/dummy_${getEntityTypeSubject(entityType)}_for_D3.png`;

export const loadImageSrc = ({ preview }) =>
  fetch(preview)
    .then(res => res.blob()
      .then(blob => readBlob.dataurl(blob)
        .then((dataUrl) => {
          const { data: image, mediaType: mimeType } = parseDataUrl(dataUrl);
          return { image, mimeType };
        })));
