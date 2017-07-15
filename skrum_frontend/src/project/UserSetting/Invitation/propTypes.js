import PropTypes from 'prop-types';

export const rolePropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
});

export const rolesPropTypes = PropTypes.arrayOf(rolePropTypes);
