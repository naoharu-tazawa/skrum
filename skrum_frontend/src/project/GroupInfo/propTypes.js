import PropTypes from 'prop-types';

export const groupPropTypes = PropTypes.shape({
  name: PropTypes.string.isRequired,
  // company: PropTypes.string.isRequired,
  // dept: PropTypes.string.isRequired,
  mission: PropTypes.string.isRequired,
  leaderName: PropTypes.string,
  lastUpdate: PropTypes.instanceOf(Date),
});

export default {
  groupPropTypes,
};
