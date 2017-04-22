const host = sub => `http://${sub}.localhost:8000`;
export default function (env = 'development') {
  return {
    host,
    env,
  };
}
