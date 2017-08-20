const host = sub => `http://${sub ? `${sub}.` : ''}localhost:8000`;
const env = 'development';
const bucket = 'https://s3-ap-northeast-1.amazonaws.com/skrumdev';
export default {
  host,
  env,
  bucket,
};
