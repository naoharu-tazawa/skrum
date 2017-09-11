import PropTypes from 'prop-types';
// import { okrTypePropType } from '../../../util/OKRUtil';
import { entityTypePropType } from '../../../util/EntityUtil';

export const ownerPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: entityTypePropType.isRequired,
});

const referenceBase = {
  id: PropTypes.number.isRequired,
  // type: okrTypePropType.isRequired,
  name: PropTypes.string.isRequired,
  owner: ownerPropTypes.isRequired,
};

const objectiveBase = {
  ...referenceBase,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.string.isRequired,
  status: PropTypes.string.isRequired,
  disclosureType: PropTypes.string.isRequired,
};

export const keyResultPropTypes = PropTypes.shape({
  ...objectiveBase,
  ratioLockedFlg: PropTypes.number,
  weightedAverageRatio: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
});

export const keyResultsPropTypes = PropTypes.arrayOf(keyResultPropTypes);

export const okrPropTypes = PropTypes.shape({
  ...objectiveBase,
  keyResults: keyResultsPropTypes.isRequired,
  parentOkr: referenceBase.isRequired,
});

export const okrsPropTypes = PropTypes.arrayOf(okrPropTypes);
