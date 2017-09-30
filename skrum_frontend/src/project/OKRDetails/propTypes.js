import PropTypes from 'prop-types';
import { okrTypePropType } from '../../util/OKRUtil';
import { entityTypePropType } from '../../util/EntityUtil';

export const ownerPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: entityTypePropType.isRequired,
});

const objectiveBase = {
  id: PropTypes.number.isRequired,
  type: okrTypePropType,
  name: PropTypes.string.isRequired,
  detail: PropTypes.string,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.number.isRequired,
  owner: ownerPropTypes.isRequired,
  status: PropTypes.string.isRequired,
  disclosureType: PropTypes.string.isRequired,
};

export const objectivePropTypes = PropTypes.shape(objectiveBase);

export const keyResultPropTypes = PropTypes.shape({
  ...objectiveBase,
  ratioLockedFlg: PropTypes.number.isRequired,
  weightedAverageRatio: PropTypes.number.isRequired,
});

export const keyResultsPropTypes = PropTypes.arrayOf(keyResultPropTypes);

export const okrPropTypes = PropTypes.shape({
  ...objectiveBase,
  keyResults: keyResultsPropTypes.isRequired,
});

export const ProgressPropTypes = PropTypes.shape({
  datetime: PropTypes.string.isRequired,
  achievementRate: PropTypes.number.isRequired,
});

export const ProgressSeriesPropTypes = PropTypes.arrayOf(ProgressPropTypes);
