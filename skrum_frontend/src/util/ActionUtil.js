export const keyValueIdentity = (key, value) => ({ [key]: value });

export const extractToken = (status) => {
  const { token } = status.auth;
  return token;
};

export const extractDomain = (status) => {
  const { company = 'company1' } = status.user;
  return company;
};
