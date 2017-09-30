import PropTypes from 'prop-types';
import { toNumber, omitBy, isUndefined, isNumber, sum, round, fromPairs } from 'lodash';
import { mapOwner } from './OwnerUtil';

export const OKRType = {
  OKR: '1',
  KR: '2',
};

export const okrTypePropType =
  PropTypes.oneOf([OKRType.OKR, OKRType.KR]);

export const mapObjective =
  ({ okrId, okrType, okrName, okrDetail, unit, targetValue, achievedValue, achievementRate,
     startDate, endDate, status, disclosureType, ratioLockedFlg, weightedAverageRatio,
     ...others }) =>
  omitBy({
    id: okrId,
    type: okrType,
    name: okrName,
    detail: okrDetail,
    unit,
    targetValue,
    achievedValue,
    achievementRate: toNumber(achievementRate),
    startDate,
    endDate,
    owner: mapOwner(others),
    status,
    disclosureType,
    ratioLockedFlg,
    weightedAverageRatio: toNumber(weightedAverageRatio),
  }, isUndefined);

export const mapOKR = (okr, keyResults = okr.keyResults, parentOkr = okr.parentOkr) =>
  omitBy({
    ...mapObjective(okr),
    keyResults: (keyResults && keyResults.map(mapObjective)) || [],
    parentOkr: parentOkr && mapObjective(parentOkr),
  }, isUndefined);

export const deriveRatios = (keyResults, overrides = {}) => {
  const ratioFallback = (ratio, krLocked, krRatio, fallback) => {
    if (isNumber(ratio)) return ratio;
    if (ratio === null) return fallback;
    return krLocked ? krRatio : fallback;
  };
  const lockedRatios = keyResults.map(({ id, weightedAverageRatio, ratioLockedFlg }) =>
    ratioFallback(overrides[id], ratioLockedFlg, weightedAverageRatio, null))
    .filter(ratio => isNumber(ratio));
  const lockedRatiosSum = sum(lockedRatios);
  const unlockedCount = keyResults.length - lockedRatios.length;
  const unlockedRatio = round((100 - lockedRatiosSum) / unlockedCount, 1);
  return {
    lockedRatiosSum,
    unlockedCount,
    unlockedRatio,
    ratios: fromPairs(
      keyResults.map(({ id, weightedAverageRatio, ratioLockedFlg }) => (
        [id, {
          weightedAverageRatio:
            ratioFallback(overrides[id], ratioLockedFlg, weightedAverageRatio, unlockedRatio),
          ratioLockedFlg: overrides[id] !== null && (overrides[id] || ratioLockedFlg) ? 1 : 0,
        }]
      ))),
  };
};
