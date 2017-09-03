export const keyValueIdentity = (key, value) => ({ [key]: value });

export const extractToken = ({ auth }) => auth.token;

export const extractSubdomain = (host = location.hostname) => {
  const hostWithoutTop = host.replace(/(\.jp$|\.work$)/, '');
  return hostWithoutTop.indexOf('.') !== -1 ? hostWithoutTop.split('.')[0] : '';
};

export const mergeUpdateById = (object, keyName, update, id) =>
  ({ ...object, ...(object[keyName] === id ? update : {}) });
