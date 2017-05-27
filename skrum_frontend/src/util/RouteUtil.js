import _ from 'lodash';

export const explodePath = (path = window.location.pathname) => {
  const [, section, id, timeframeId, tab, subSection, subId] = path.split('/');
  const basicParts = { section, id, timeframeId, tab };
  return subSection ? { ...basicParts, subSection, subId } : basicParts;
};

export const implodePath = ({ section, id, timeframeId, tab, subSection, subId }) =>
  `/${section}/${id}/${timeframeId}/${tab}${subSection ? `/${subSection}/${subId}` : ''}`;

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
