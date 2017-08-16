import PropTypes from 'prop-types';

export const userPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  roleAssignmentId: PropTypes.number.isRequired,
  roleLevel: PropTypes.number.isRequired,
  lastLogin: PropTypes.string,
});

export const usersPropTypes = PropTypes.arrayOf(userPropTypes);
