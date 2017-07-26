import PropTypes from 'prop-types';
import { entityTypePropType } from '../../../util/EntityUtil';

export const keyResultPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.string.isRequired,
  owner: PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    type: entityTypePropType.isRequired,
  }).isRequired,
  status: PropTypes.string.isRequired,
  ratioLockedFlg: PropTypes.number.isRequired,
});

export const keyResultsPropTypes = PropTypes.arrayOf(keyResultPropTypes);

export const okrPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.string.isRequired,
  owner: PropTypes.shape({
    id: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
  }).isRequired,
  keyResults: keyResultsPropTypes.isRequired,
  status: PropTypes.string.isRequired,
  ratioLockedFlg: PropTypes.number,
});

export const okrsPropTypes = PropTypes.arrayOf(okrPropTypes);
