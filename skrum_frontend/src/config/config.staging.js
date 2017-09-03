const host = sub => `http://${sub ? `${sub}.` : ''}skrum-api-stg.space`;
const env = 'staging';
const bucket = 'https://s3-ap-northeast-1.amazonaws.com/skrumstg';
export default {
  host,
  env,
  bucket,
};
