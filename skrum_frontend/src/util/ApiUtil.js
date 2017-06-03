import { extractToken, extractDomain } from './ActionUtil';
import config from '../config/config';

const { host } = config;
const getBaseUrl = sub => `${host(sub)}/v1`;
const getUrlParam = (param) => {
  if (!param) return '';
  const toQuery = (k, v) => `${k}=${v}`;
  const params = Object.keys(param)
    .filter(key => param[key])
    .map((key) => {
      const value = param[key];
      if (value instanceof Array && value.length > 0) {
        return value.map(el => toQuery(key, el)).join('&');
      }
      return toQuery(key, value);
    });
  return `?${params.join('&')}`;
};

const createUrl = (path, param, status) => {
  const domain = extractDomain(status);
  const url = getBaseUrl(domain);
  const urlParam = getUrlParam(param);
  return url + path + urlParam;
};

const createOption = (method, status, body) => {
  const headers = {};
  const authToken = extractToken(status);
  if (authToken) {
    headers.Authorization = authToken;
  }
  headers['Content-Type'] = 'application/json;';

  return {
    method,
    mode: 'cors',
    cache: 'no-cache',
    headers,
    body: JSON.stringify(body),
  };
};

const getErrorMessage = (code) => {
  switch (code) {
    case 400:
      return '不正なリクエストです。';
    case 401:
      return 'ログインしてください。';
    case 403:
      return 'アクセス権限がありません。';
    case 404:
      return 'ページがありません。';
    case 405:
      return '不正なリクエストです。';
    case 409:
      return '他のユーザーが編集中のため、編集できません。';
    case 500:
      return 'エラーが発生しました。';
    case 503:
      return 'サービスが一時的に停止しています。';
    default:
      return `エラー${code}`;
  }
};

export const handleResponse = (data) => {
  const status = data.status;
  if (status === 200) {
    return data.json();
  }
  return data.json()
    .then((json) => {
      const { code, reason, message, errors } = json;
      const finalMessage = message && message !== '' ? message : getErrorMessage(code);
      const exception = { code, status, reason, errors, message: finalMessage };
      throw exception;
    });
};

export const handleError = (error) => {
  const exception = {
    message: error.message,
    detail: error.stack,
  };
  throw exception;
};

export const getJson = (path, status) => (param) => {
  const url = createUrl(path, param, status);
  const op = createOption('GET', status);
  return fetch(url, op)
    .catch(handleError)
    .then(handleResponse);
};

export const postJson = (path, status) => (param, body) => {
  const url = createUrl(path, param, status);
  const op = createOption('POST', status, body);
  return fetch(url, op)
    .catch(handleError)
    .then(handleResponse);
};

export const putJson = (path, status) => (param, body) => {
  const url = createUrl(path, param, status);
  const op = createOption('PUT', status, body);
  return fetch(url, op)
    .catch(handleError)
    .then(handleResponse);
};

export const deleteJson = (path, status) => (param, body) => {
  const url = createUrl(path, param, status);
  const op = createOption('DELETE', status, body);
  return fetch(url, op)
    .catch(handleError)
    .then(handleResponse);
};
