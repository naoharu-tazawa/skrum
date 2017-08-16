import readBlob from 'read-blob';
import parseDataUrl from 'parse-data-url';
import { implodeSubject } from './RouteUtil';
import { getEntityTypeSubject } from './EntityUtil';

const s3BucketBase = 'https://s3-ap-northeast-1.amazonaws.com';
let s3Bucket;

if (process.env.NODE_ENV === 'production') {
  s3Bucket = `${s3BucketBase}/skrum`;
} else {
  s3Bucket = `${s3BucketBase}/skrumdev`;
}

export const imagePath = (entityType, companyId, id) => `
  ${s3Bucket}
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
