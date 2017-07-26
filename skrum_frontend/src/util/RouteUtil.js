import { toNumber, isEmpty, pickBy, isUndefined } from 'lodash';
import { EntityType } from './EntityUtil';

export const explodeSubject = (section) => {
  switch (section && section[0]) {
    case 'u': return 'user';
    case 'g': return 'group';
    case 'c': return 'company';
    default: return undefined;
  }
};

export const implodeSubject = (section) => {
  switch (section) {
    case 'user': case EntityType.USER: return 'u';
    case 'group': case EntityType.GROUP: return 'g';
    case 'company': case EntityType.COMPANY: return 'c';
    default: return undefined;
  }
};

export const explodeTab = (tab) => {
  switch (tab && tab[0]) {
    case 'o': return 'objective';
    case 'm': return 'map';
    case 't': return 'timeline';
    case 'c': return 'control';
    default: return undefined;
  }
};

export const implodeTab = (tab) => {
  switch (tab) {
    case 'objective': return 'o';
    case 'map': return 'm';
    case 'timeline': return 't';
    case 'control': return 'c';
    default: return undefined;
  }
};

export const explodePath = (path = location.pathname, options = {}) => {
  const [, subject, id, timeframeId, tab, aspect, aspectId] = path.split('/');
  const basicParts = {
    subject: explodeSubject(subject),
    id: toNumber(id),
    timeframeId: toNumber(timeframeId),
    tab: explodeTab(tab),
  };
  const { basicOnly = false } = options;
  return basicOnly || !aspect ? basicParts : {
    ...basicParts, aspect, aspectId: toNumber(aspectId) };
};

export const implodePath = ({ subject, id, timeframeId, tab, aspect, aspectId }, options = {}) => {
  const { basicOnly = false } = options;
  return `
    /${implodeSubject(subject)}
    /${id}
    /${timeframeId}
    /${implodeTab(tab)}
    ${!basicOnly && aspect ? `/${aspect}/${aspectId}` : ''}`
    .replace(/[\s\n]/g, '');
};

export const replacePath = (components, options = {}) =>
  implodePath({ ...explodePath(location.pathname, options), ...components });

export const toBasicPath = (path = location.pathname) =>
  implodePath(explodePath(path, { basicOnly: true }));

export const comparePath = (pathname1, pathname2, options = {}) =>
  implodePath(explodePath(pathname1, options)) === implodePath(explodePath(pathname2, options));

export const isPathFinal = (path = location.pathname) =>
  isEmpty(pickBy(explodePath(path), isUndefined));
