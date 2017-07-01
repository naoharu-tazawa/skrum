export const keyValueIdentity = (key, value) => ({ [key]: value });

export const extractToken = ({ auth }) => auth.token;

export const extractDomain = () => location.hostname.split('.')[0];

export const mergeUpdateById = (object, keyName, update, id) =>
  ({ ...object, ...(object[keyName] === id ? update : {}) });
