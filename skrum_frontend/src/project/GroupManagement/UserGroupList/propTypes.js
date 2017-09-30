import PropTypes from 'prop-types';

export const userGroupPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  achievementRate: PropTypes.number.isRequired,
});

export const userGroupsPropTypes = PropTypes.arrayOf(userGroupPropTypes);
