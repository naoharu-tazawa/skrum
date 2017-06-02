import PropTypes from 'prop-types';

export const AlignmentPropTypes = PropTypes.shape({
  ownerType: PropTypes.oneOf(['1', '2', '3']).isRequired,
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
