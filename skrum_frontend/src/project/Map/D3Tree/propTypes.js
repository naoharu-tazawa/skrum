import PropTypes from 'prop-types';

export const d3treePropTypes = PropTypes.shape({
  okrId: PropTypes.number.isRequired,
  okrName: PropTypes.string.isRequired,
  achievementRate: PropTypes.number.isRequired,
  ownerType: PropTypes.string.isRequired,
  ownerUserId: PropTypes.number.isRequired,
  ownerUserName: PropTypes.string.isRequired,
  children: PropTypes.array,
});

export const d3treesPropTypes = PropTypes.arrayOf(d3treePropTypes);
