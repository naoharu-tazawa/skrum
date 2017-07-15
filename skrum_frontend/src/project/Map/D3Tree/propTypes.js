import PropTypes from 'prop-types';

export const d3treePropTypes = PropTypes.shape({
  okrId: PropTypes.number,
  okrName: PropTypes.string,
  targetValue: PropTypes.number,
  achievedValue: PropTypes.number,
  unit: PropTypes.string,
  achievementRate: PropTypes.number,
  ownerType: PropTypes.string,
  ownerUserId: PropTypes.number,
  ownerUserName: PropTypes.string,
  ownerGroupId: PropTypes.number,
  ownerGroupName: PropTypes.string,
  ownerCompanyId: PropTypes.number,
  ownerCompanyName: PropTypes.string,
  status: PropTypes.string,
  hidden: PropTypes.boolean,
  children: PropTypes.array,
});

export const d3treesPropTypes = PropTypes.arrayOf(d3treePropTypes);
