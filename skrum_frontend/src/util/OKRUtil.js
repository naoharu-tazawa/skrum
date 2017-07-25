import { omitBy, isUndefined } from 'lodash';
import { mapOwner } from './OwnerUtil';

export const mapKeyResult = (kr) => {
  const { okrId, okrType, okrName, okrDetail, unit, targetValue, achievedValue, achievementRate,
    startDate, endDate, status, disclosureType, ratioLockedFlg } = kr;
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
    ratioLockedFlg,
  };
};

export const mapOKR = (okr, keyResults = okr.keyResults || []) => {
  return omitBy(
    { ...mapKeyResult(okr), keyResults: keyResults && keyResults.map(mapKeyResult) },
    isUndefined);
};
