import { omitBy, isUndefined } from 'lodash';
import { mapOwner } from './OwnerUtil';

export const mapKeyResult = (kr) => {
  const { okrId, okrName, okrDetail, unit, targetValue, achievedValue, achievementRate,
    status, ratioLockedFlg } = kr;
  return {
    id: okrId,
    name: okrName,
    detail: okrDetail,
    unit,
    targetValue,
    achievedValue,
    achievementRate,
    owner: mapOwner(kr),
    status,
    ratioLockedFlg,
  };
};

export const mapOKR = (okr, keyResults = okr.keyResults) => {
  return omitBy(
    { ...mapKeyResult(okr), keyResults: keyResults && keyResults.map(mapKeyResult) },
    isUndefined);
};
