import PropTypes from 'prop-types';
import { omitBy, isUndefined } from 'lodash';
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
    achievementRate,
    startDate,
    endDate,
    owner: mapOwner(others),
    status,
    disclosureType,
    ratioLockedFlg,
    weightedAverageRatio,
  }, isUndefined);

export const mapOKR = (okr, keyResults = okr.keyResults, parentOkr = okr.parentOkr) =>
  omitBy({
    ...mapObjective(okr),
    keyResults: (keyResults && keyResults.map(mapObjective)) || [],
    parentOkr: parentOkr && mapObjective(parentOkr),
  }, isUndefined);
