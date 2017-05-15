import PropTypes from 'prop-types';

export const companyPropTypes = PropTypes.shape({
  name: PropTypes.string.isRequired,
  mission: PropTypes.string.isRequired,
  vision: PropTypes.string,
  lastUpdate: PropTypes.instanceOf(Date),
});

export default {
  companyPropTypes,
};
