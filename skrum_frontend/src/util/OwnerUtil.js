
export const getOwnerTypeId = (ownerType) => {
  switch (ownerType) {
    case 'user': return '1';
    case 'group': return '2';
    case 'company': return '3';
    default: return undefined;
  }
};

export const getOwnerTypeSubject = (ownerType) => {
  switch (ownerType) {
    case '1': return 'User';
    case '2': return 'Group';
    case '3': return 'Company';
    default: return undefined;
  }
};

export const mapOwner = (data) => {
  const { ownerType } = data;
  const ownerSubject = `owner${getOwnerTypeSubject(ownerType)}`;
  const { [`${ownerSubject}Id`]: ownerId, [`${ownerSubject}Name`]: ownerName } = data;
  return { id: ownerId, name: ownerName, type: ownerType };
};

export const mapOwnerOutbound = ({ type, id, name }) => ({
  ownerType: type,
  [`owner${getOwnerTypeSubject(type)}Id`]: id,
  [`owner${getOwnerTypeSubject(type)}Name`]: name,
});
