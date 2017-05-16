import PropTypes from 'prop-types';

export const userPropTypes = PropTypes.shape({
  name: PropTypes.string.isRequired,
  departments: PropTypes.arrayOf(PropTypes.shape({
    groupId: PropTypes.number.isRequired,
    groupName: PropTypes.string.isRequired,
  })).isRequired,
  position: PropTypes.string.isRequired,
  phoneNumber: PropTypes.string,
  emailAddress: PropTypes.string,
});

export default {
  userPropTypes,
};
