import PropTypes from 'prop-types';
import { entityTypePropType } from '../../../util/EntityUtil';

export const AlignmentPropTypes = PropTypes.shape({
  ownerType: entityTypePropType.isRequired,
  group: PropTypes.shape({
    groupId: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    numberOfOkrs: PropTypes.number.isRequired,
  }),
  company: PropTypes.shape({
    companyId: PropTypes.number.isRequired,
    name: PropTypes.string.isRequired,
    numberOfOkrs: PropTypes.number.isRequired,
  }),
});

export const AlignmentsInfoPropTypes = PropTypes.arrayOf(AlignmentPropTypes);
