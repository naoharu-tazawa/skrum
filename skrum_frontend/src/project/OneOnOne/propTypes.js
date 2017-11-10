import PropTypes from 'prop-types';
import { entityPropTypes } from '../../util/EntityUtil';

export const oneOnOneTypes = {
  dailyReport: '日報',
  progressMemo: '進捗報告',
  hearing: 'ヒアリング',
  feedback: 'フィードバック',
  interviewNote: '面談メモ',
};

export const feedbackTypes = { 1: 'ありがとう', 2: 'アドバイス', 3: 'グッジョブ', 4: '課題点' };

export const oneOnOneTypePropType = PropTypes.oneOf(['1', '2', '3', '4', '5']);

export const notePropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  type: oneOnOneTypePropType.isRequired,
  sender: entityPropTypes.isRequired,
  toNames: PropTypes.string, // .isRequired,
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
