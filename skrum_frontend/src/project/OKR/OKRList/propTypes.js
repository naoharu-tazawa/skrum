import PropTypes from 'prop-types';

export const okrPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  achievementRate: PropTypes.string.isRequired,
  owner: PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
  }).isRequired,
  status: PropTypes.string.isRequired,
});

export const okrsPropTypes = PropTypes.arrayOf(okrPropTypes);
