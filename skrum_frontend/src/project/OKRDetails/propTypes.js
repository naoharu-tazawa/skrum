import PropTypes from 'prop-types';
import { okrTypePropType } from '../../util/OKRUtil';
import { entityTypePropType } from '../../util/EntityUtil';

const objectiveBase = {
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  detail: PropTypes.string,
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
  disclosureType: PropTypes.string,
  weightedAverageRatio: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  ratioLockedFlg: PropTypes.number,
};

export const objectivePropTypes = PropTypes.shape(objectiveBase);

export const keyResultPropTypes = PropTypes.shape({
  ...objectiveBase,
  type: okrTypePropType.isRequired,
  ratioLockedFlg: PropTypes.number.isRequired,
});

export const keyResultsPropTypes = PropTypes.arrayOf(keyResultPropTypes);

export const okrPropTypes = PropTypes.shape({
  ...objectiveBase,
  keyResults: keyResultsPropTypes.isRequired,
});

export const ProgressPropTypes = PropTypes.shape({
  datetime: PropTypes.string.isRequired,
  achievementRate: PropTypes.string.isRequired,
});

export const ProgressSeriesPropTypes = PropTypes.arrayOf(ProgressPropTypes);
