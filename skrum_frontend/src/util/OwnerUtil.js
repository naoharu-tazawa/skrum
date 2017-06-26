
export const getOwnerTypeSubject = (ownerType) => {
  switch (ownerType) {
    case '1': return 'User';
    case '2': return 'Group';
    case '3': return 'Company';
    default: return '';
  }
};

export const mapOwner = (data) => {
  const { ownerType } = data;
  const ownerSubject = `owner${getOwnerTypeSubject(ownerType)}`;
  const { [`${ownerSubject}Id`]: ownerId, [`${ownerSubject}Name`]: ownerName } = data;
  return { id: ownerId, name: ownerName, type: ownerType };
};
