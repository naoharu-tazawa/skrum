import PropTypes from 'prop-types';
import { toPairs, flatten, invert, toNumber, isEmpty, pickBy, isUndefined } from 'lodash';
import { EntityType } from './EntityUtil';

const tabs = {
  o: 'objective',
  m: 'map',
  t: 'timeline',
  c: 'control',
  s: 'setting',
};

export const tabPropType = PropTypes.oneOf(flatten(toPairs(tabs)));

export const explodeTab = tab => tabs[tab && tab[0]];

export const implodeTab = tab => invert(tabs)[tab];

const subjects = {
  u: 'user',
  g: 'group',
  c: 'company',
  t: 'timeframe',
  e: 'email',
  a: 'account',
};

export const subjectPropType = PropTypes.oneOf(flatten(toPairs(subjects)));

export const explodeSubject = subject => subjects[subject && subject[0]];

export const implodeSubject = (subject) => {
  switch (subject) {
    case EntityType.USER: return 'u';
    case EntityType.GROUP: return 'g';
    case EntityType.COMPANY: return 'c';
    default: return invert(subjects)[subject];
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
