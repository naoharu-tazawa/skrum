import PropTypes from 'prop-types';

export const userPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  roleAssignmentId: PropTypes.number.isRequired,
  roleAssignmentName: PropTypes.string.isRequired,
  lastLogin: PropTypes.string.isRequired,
});

export const usersPropTypes = PropTypes.arrayOf(userPropTypes);
