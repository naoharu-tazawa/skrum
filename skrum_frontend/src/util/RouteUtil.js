import _ from 'lodash';

export const explodePath = (path = window.location.pathname) => {
  const [, section, id, timeframeId, tab] = path.split('/');
  return { section, id, timeframeId, tab };
};

export const implodePath = ({ section, id, timeframeId, tab }) =>
  `/${section}/${id}/${timeframeId}/${tab}`;

export const replacePath = components =>
  implodePath({ ...explodePath(), ...components });

export const isPathFinal = (path = window.location.pathname) =>
  _.isEmpty(_.pickBy(explodePath(path), _.isUndefined));

export default {
  explodePath,
  implodePath,
  replacePath,
  isPathFinal,
};
