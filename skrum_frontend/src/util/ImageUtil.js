import readBlob from 'read-blob';
import parseDataUrl from 'parse-data-url';
import { implodeSubject } from './RouteUtil';
import { getEntityTypeSubject } from './EntityUtil';

const skrumBucket = 'https://s3-ap-northeast-1.amazonaws.com/skrumdev';

export const imagePath = (entityType, companyId, id) => `
  ${skrumBucket}
  /c
  /${companyId}
  ${implodeSubject(entityType) === 'c' ? '' : `/${implodeSubject(entityType)}/${id}`}
  /image1
  `.replace(/[\s\n]/g, '');

export const dummyImagePath = entityType =>
  `/img/dummy_${getEntityTypeSubject(entityType)}.svg`;

export const loadImageSrc = ({ preview }) =>
  fetch(preview)
    .then(res => res.blob()
      .then(blob => readBlob.dataurl(blob)
        .then((dataUrl) => {
          const { data: image, mediaType: mimeType } = parseDataUrl(dataUrl);
          return { image, mimeType };
        })));

// export const loadImageSrc = file => console.log(file);
