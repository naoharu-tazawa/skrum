import PropTypes from 'prop-types';

export const groupPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  groupType: PropTypes.string.isRequired,
});

export const groupsPropTypes = PropTypes.arrayOf(groupPropTypes);
