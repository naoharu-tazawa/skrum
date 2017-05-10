export const keyValueIdentity = (key, value) => ({ [key]: value });

export const extractToken = (status) => {
  const { token } = status.auth;
  return token;
};

export const extractDomain = () => {
  const path = location.hostname;
  const bases = path.split('.');
  const subdomain = bases[0];
  return subdomain;
};
