import PropTypes from 'prop-types';
import { toPairs, fromPairs, keys } from 'lodash';
import { entityPropTypes } from '../../util/EntityUtil';

export const oneOnOneTypes = {
  dailyReport: '日報',
  progressMemo: '進捗メモ',
  hearing: 'ヒアリング',
  feedback: 'フィードバック',
  interviewNote: '面談ノート',
};

export const oneOnOneTypeKeys = fromPairs(toPairs(oneOnOneTypes)
  .map(([key], index) => [index + 1, key]));

export const oneOnOneTypePropType = PropTypes.oneOf(keys(oneOnOneTypeKeys));

export const feedbackTypes = { 1: 'ありがとう', 2: 'アドバイス', 3: 'グッジョブ', 4: '課題点' };

export const notePropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  type: oneOnOneTypePropType.isRequired,
  sender: entityPropTypes.isRequired,
  toNames: PropTypes.string, // .isRequired,
  intervieweeUserName: PropTypes.string,
  lastUpdate: PropTypes.string.isRequired,
  text: PropTypes.string.isRequired,
  read: PropTypes.bool,
});

export const notesPropTypes = PropTypes.arrayOf(notePropTypes);

export const queryPropTypes = PropTypes.shape({
  startDate: PropTypes.string.isRequired,
  endDate: PropTypes.string.isRequired,
  keyword: PropTypes.string.isRequired,
});
