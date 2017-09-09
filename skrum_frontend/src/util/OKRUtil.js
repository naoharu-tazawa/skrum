import PropTypes from 'prop-types';
import { omitBy, isUndefined } from 'lodash';
import { mapOwner } from './OwnerUtil';

export const OKRType = {
  OKR: '1',
  KR: '2',
};

export const okrTypePropType =
  PropTypes.oneOf([OKRType.OKR, OKRType.KR]);

export const mapKeyResult = (kr) => {
  const { okrId, okrType, okrName, okrDetail, unit, targetValue, achievedValue, achievementRate,
    startDate, endDate, status, disclosureType, weightedAverageRatio, ratioLockedFlg } = kr;
  return {
    id: okrId,
    type: okrType,
    name: okrName,
    detail: okrDetail,
    unit,
    targetValue,
    achievedValue,
    achievementRate,
    startDate,
    endDate,
    owner: mapOwner(kr),
    status,
    disclosureType,
    weightedAverageRatio,
    ratioLockedFlg,
  };
};

export const mapOKR = (okr, keyResults = okr.keyResults || []) => {
  return omitBy(
    { ...mapKeyResult(okr), keyResults: keyResults && keyResults.map(mapKeyResult) },
    isUndefined);
};
