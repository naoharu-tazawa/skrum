export const keyValueIdentity = (key, value) => ({ [key]: value });

const defaultToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhY2NvdW50cy5za3J1bS5qcCIsImlhdCI6MTQ5MTYzNDc5MSwiZXhwIjoxNTA3MTg2NzkxLCJzZG0iOiJjb21wYW55MSIsInVpZCI6MSwiY2lkIjoxLCJyaWQiOiJBMDAzIiwicmx2Ijo3LCJwZXJtaXNzaW9ucyI6WyJjb21wYW55X3Byb2ZpbGVfZWRpdCIsInVzZXJfYWRkIiwidXNlcl9kZWxldGUiLCJ1c2VyX3Blcm1pc3Npb25fY2hhbmdlIiwicGFzc3dvcmRfcmVzZXQiLCJ0aW1lZnJhbWVfZWRpdCIsInBsYW5fZWRpdCIsInBheW1lbnRfdmlldyJdfQ.5qhe2t9cCJLC3bN-aqVOI52Ur4hme7nAB1i8dcVi5Og';
export const extractToken = () => {
  // const { token } = status.auth;
  return defaultToken;
};

export const extractDomain = (status) => {
  const { company = 'company1' } = status.auth;
  return company;
};
