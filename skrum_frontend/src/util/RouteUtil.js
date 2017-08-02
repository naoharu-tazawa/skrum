import PropTypes from 'prop-types';
import { toNumber, isEmpty, pickBy, isUndefined } from 'lodash';
import { EntityType } from './EntityUtil';

export const tabPropType =
  PropTypes.oneOf(['objective', 'map', 'timeline', 'control', 'setting']);

export const explodeTab = (tab) => {
  switch (tab && tab[0]) {
    case 'o': return 'objective';
    case 'm': return 'map';
    case 't': return 'timeline';
    case 'c': return 'control';
    case 's': return 'setting';
    default: return undefined;
  }
};

export const implodeTab = (tab) => {
  switch (tab) {
    case 'objective': return 'o';
    case 'map': return 'm';
    case 'timeline': return 't';
    case 'control': return 'c';
    case 'setting': return 's';
    default: return undefined;
  }
};

export const subjectPropType =
  PropTypes.oneOf(['user', 'group', 'company', 'timeframe', 'account']);

export const explodeSubject = (section) => {
  switch (section && section[0]) {
    case 'u': return 'user';
    case 'g': return 'group';
    case 'c': return 'company';
    case 't': return 'timeframe';
    case 'a': return 'account';
    default: return undefined;
  }
};

export const implodeSubject = (section) => {
  switch (section) {
    case 'user': case EntityType.USER: return 'u';
    case 'group': case EntityType.GROUP: return 'g';
    case 'company': case EntityType.COMPANY: return 'c';
    case 'timeframe': return 't';
    case 'account': return 'a';
    default: return undefined;
  }
};

export const explodePath = (path, options = {}) => {
  const [, tab, subject, id, timeframeId, aspect, aspectId] = path.split('/');
  const basicParts = {
    tab: explodeTab(tab),
    subject: explodeSubject(subject),
    id: toNumber(id),
    timeframeId: toNumber(timeframeId),
  };
  const { basicOnly = false } = options;
  return basicOnly || !aspect ? basicParts : {
    ...basicParts, aspect, aspectId: toNumber(aspectId) };
};

export const implodePath = ({ tab, subject, id, timeframeId, aspect, aspectId }, options = {}) => {
  const { basicOnly = false } = options;
  const isSetting = tab === 'setting';
  return `
    /${implodeTab(tab)}
    /${implodeSubject(subject)}
    ${!isSetting ? `/${id}` : ''}
    ${!isSetting ? `/${timeframeId}` : ''}
    ${!isSetting && !basicOnly && aspect ? `/${aspect}/${aspectId}` : ''}
    `.replace(/[\s\n]/g, '');
};

export const replacePath = (components, options = {}) =>
  implodePath({ ...explodePath(location.pathname, options), ...components });

export const toBasicPath = path =>
  implodePath(explodePath(path, { basicOnly: true }));

export const comparePath = (pathname1, pathname2, options = {}) =>
  implodePath(explodePath(pathname1, options)) === implodePath(explodePath(pathname2, options));

export const isPathFinal = path =>
  isEmpty(pickBy(explodePath(path), isUndefined));
