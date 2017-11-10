import { omitBy, isUndefined } from 'lodash';
import { EntityType, mapEntity } from './EntityUtil';

export const mapOneOnOne =
  ({ oneOnOneId, oneOnOneType, toNames, intervieweeUserName, lastUpdate, partOfText, text, readFlg,
  ...noteOthers }) =>
  omitBy({
    id: oneOnOneId,
    type: oneOnOneType,
    sender: mapEntity(noteOthers, 'sender', EntityType.USER),
    toNames,
    intervieweeUserName,
    lastUpdate,
    text: text || partOfText,
    read: readFlg === 1,
  }, isUndefined);

export default { mapOneOnOne };
