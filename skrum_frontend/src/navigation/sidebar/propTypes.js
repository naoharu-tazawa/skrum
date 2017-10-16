import PropTypes from 'prop-types';

export const itemsPropTypes = PropTypes.arrayOf(PropTypes.shape({
  id: PropTypes.number.isRequired,
  title: PropTypes.string.isRequired,
}));

export const sectionPropTypes = PropTypes.shape({
  title: PropTypes.string.isRequired,
  items: itemsPropTypes.isRequired,
});

export const sectionsPropTypes = PropTypes.arrayOf(sectionPropTypes);
