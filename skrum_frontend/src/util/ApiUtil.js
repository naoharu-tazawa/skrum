import { extractToken, extractDomain } from './ActionUtil';
import config from '../config/config';

const { host } = config;
const getBaseUrl = sub => `${host(sub)}/v1`;
const getUrlParam = (param) => {
  if (!param) return '';
  const toQuery = (k, v) => `${k}=${v}`;
  const params = [];
  Object.keys(param)
    .forEach((key) => {
      const value = param[key];
      if (value) {
        if (value.length > 0) {
          value.forEach(el => params.push(toQuery(key, el)));
        }

        params.push(toQuery(key, value));
      }
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

export const handleResponse = (data) => {
  const status = data.status;
  if (status === 200) {
    return data.json();
  }

  return data.json()
    .then((json) => {
      const exception = {
        status,
        message: json.message,
        errors: json.errors,
      };
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
  console.log(url);
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
