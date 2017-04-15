import PropTypes from 'prop-types';

export const items = PropTypes.arrayOf(PropTypes.shape({
  title: PropTypes.string.isRequired,
  imgSrc: PropTypes.string.isRequired,
})).isRequired;

export const sections = PropTypes.arrayOf(PropTypes.shape({
  title: PropTypes.string.isRequired,
  items,
})).isRequired;
