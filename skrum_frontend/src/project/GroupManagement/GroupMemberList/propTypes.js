import PropTypes from 'prop-types';

export const groupMemberPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  position: PropTypes.string.isRequired,
  lastLogin: PropTypes.string.isRequired,
});

export const groupMembersPropTypes = PropTypes.arrayOf(groupMemberPropTypes);
