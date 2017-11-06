import { omitBy, isUndefined } from 'lodash';
import { EntityType, mapEntity } from './EntityUtil';

export const mapOneOnOne =
  ({ oneOnOneId, oneOnOneType, toNames, intervieweeUserName, lastUpdate, partOfText, readFlg,
  ...noteOthers }) =>
  omitBy({
    id: oneOnOneId,
    type: oneOnOneType,
    sender: mapEntity(noteOthers, 'sender', EntityType.USER),
    toNames,
    intervieweeUserName,
    lastUpdate,
    preview: partOfText,
    read: readFlg === 1,
  }, isUndefined);

export default { mapOneOnOne };
