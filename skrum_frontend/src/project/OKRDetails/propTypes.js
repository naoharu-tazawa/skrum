import PropTypes from 'prop-types';

export const keyResultPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  detail: PropTypes.string,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.string.isRequired,
  owner: PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    type: PropTypes.oneOf(['1', '2', '3']).isRequired,
  }).isRequired,
  status: PropTypes.string.isRequired,
  ratioLockedFlg: PropTypes.number.isRequired,
});

export const keyResultsPropTypes = PropTypes.arrayOf(keyResultPropTypes);

export const okrPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  detail: PropTypes.string,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.string.isRequired,
  owner: PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
  }).isRequired,
  keyResults: keyResultsPropTypes.isRequired,
  status: PropTypes.string.isRequired,
  weightedAverageRatio: PropTypes.string,
  ratioLockedFlg: PropTypes.number,
});

export const ProgressPropTypes = PropTypes.shape({
  datetime: PropTypes.string.isRequired,
  achievementRate: PropTypes.string.isRequired,
});

export const ProgressSeriesPropTypes = PropTypes.arrayOf(ProgressPropTypes);
