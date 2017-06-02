
export const getOwnerTypeSubject = (ownerType) => {
  switch (ownerType) {
    case '1': return 'User';
    case '2': return 'Group';
    case '3': return 'Company';
    default: return '';
  }
};

export const mapKeyResult = (kr) => {
  const { okrId, okrName, okrDetail, unit, targetValue, achievedValue, achievementRate,
    ownerType, status, ratioLockedFlg } = kr;
  const ownerSubject = `owner${getOwnerTypeSubject(ownerType)}`;
  const { [`${ownerSubject}Id`]: ownerId, [`${ownerSubject}Name`]: ownerName } = kr;
  return {
    id: okrId,
    name: okrName,
    detail: okrDetail,
    unit,
    targetValue,
    achievedValue,
    achievementRate,
    owner: { id: ownerId, name: ownerName, type: ownerType },
    status,
    ratioLockedFlg,
  };
};

export const mapOKR = (okr, keyResults = okr.keyResults || []) => {
  return { ...mapKeyResult(okr), keyResults: keyResults.map(mapKeyResult) };
};
