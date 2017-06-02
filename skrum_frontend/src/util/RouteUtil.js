import _ from 'lodash';

export const explodePath = (path = window.location.pathname, options = {}) => {
  const [, subject, id, timeframeId, tab, aspect, aspectId] = path.split('/');
  const basicParts = { subject, id, timeframeId, tab };
  const { basicOnly = false } = options;
  return basicOnly || !aspect ? basicParts : { ...basicParts, aspect, aspectId };
};

export const implodePath = ({ subject, id, timeframeId, tab, aspect, aspectId }, options = {}) => {
  const { basicOnly = false } = options;
  return `/${subject}/${id}/${timeframeId}/${tab}${!basicOnly && aspect ? `/${aspect}/${aspectId}` : ''}`;
};

export const replacePath = (components, options = {}) =>
  implodePath({ ...explodePath(window.location.pathname, options), ...components });

export const comparePath = (pathname1, pathname2, options = {}) =>
  implodePath(explodePath(pathname1, options)) === implodePath(explodePath(pathname2, options));

export const isPathFinal = (path = window.location.pathname) =>
  _.isEmpty(_.pickBy(explodePath(path), _.isUndefined));

export default {
  explodePath,
  implodePath,
  replacePath,
  isPathFinal,
};
