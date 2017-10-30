import PropTypes from 'prop-types';
// import { okrTypePropType } from '../../../util/OKRUtil';
import { entityTypePropType } from '../../../util/EntityUtil';

export const ownerPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: entityTypePropType.isRequired,
});

const objectiveReference = {
  id: PropTypes.number.isRequired,
  // type: okrTypePropType.isRequired,
  name: PropTypes.string.isRequired,
  owner: ownerPropTypes.isRequired,
};

export const objectiveReferencePropTypes = PropTypes.shape(objectiveReference);

const objectiveBase = {
  ...objectiveReference,
  unit: PropTypes.string.isRequired,
  targetValue: PropTypes.number.isRequired,
  achievedValue: PropTypes.number.isRequired,
  achievementRate: PropTypes.number.isRequired,
  status: PropTypes.string.isRequired,
  disclosureType: PropTypes.string.isRequired,
};

export const objectivePropTypes = PropTypes.shape(objectiveBase);

export const keyResultPropTypes = PropTypes.shape({
  ...objectiveBase,
  ratioLockedFlg: PropTypes.number,
  weightedAverageRatio: PropTypes.number.isRequired,
});

export const keyResultsPropTypes = PropTypes.arrayOf(keyResultPropTypes);

export const okrPropTypes = PropTypes.shape({
  ...objectiveBase,
  keyResults: keyResultsPropTypes.isRequired,
  parentOkr: objectiveReferencePropTypes,
});

export const okrsPropTypes = PropTypes.arrayOf(okrPropTypes);
