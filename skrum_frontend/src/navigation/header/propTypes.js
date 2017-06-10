import PropTypes from 'prop-types';

export const tabPropType = PropTypes.oneOf(['o', 'objective', 'm', 'map', 't', 'timeline', 'c', 'control']);

export const timeframePropTypes = PropTypes.shape({
  timeframeId: PropTypes.number.isRequired,
  timeframeName: PropTypes.string.isRequired,
  defaultFlg: PropTypes.number,
});

export const timeframesPropTypes = PropTypes.arrayOf(timeframePropTypes);
