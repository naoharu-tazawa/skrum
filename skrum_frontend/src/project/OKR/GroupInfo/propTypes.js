import PropTypes from 'prop-types';

export const groupPropTypes = PropTypes.shape({
  groupId: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  groupPaths: PropTypes.arrayOf(PropTypes.shape({
    groupTreeId: PropTypes.number.isRequired,
    groupPath: PropTypes.arrayOf(PropTypes.shape({
      id: PropTypes.number.isRequired,
      name: PropTypes.string.isRequired,
    })).isRequired,
  })).isRequired,
  mission: PropTypes.string,
  leaderName: PropTypes.string,
  lastUpdate: PropTypes.string,
});

export default {
  groupPropTypes,
};
