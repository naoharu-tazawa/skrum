import PropTypes from 'prop-types';

export const oneOnOneSettingPropTypes = PropTypes.shape({
  type: PropTypes.number.isRequired,
  to: PropTypes.arrayOf(PropTypes.shape({
    userId: PropTypes.number.isRequired,
  })).isRequired,
});

export const oneOnOneSettingsPropTypes = PropTypes.arrayOf(oneOnOneSettingPropTypes);
