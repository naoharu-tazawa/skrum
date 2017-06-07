import PropTypes from 'prop-types';

export const timeframePropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  startDate: PropTypes.string.isRequired,
  endDate: PropTypes.string.isRequired,
  defaultFlg: PropTypes.number,
});

export const timeframesPropTypes = PropTypes.arrayOf(timeframePropTypes);
