import PropTypes from 'prop-types';

export const d3treePropTypes = PropTypes.shape({
  name: PropTypes.string.isRequired,
  parent: PropTypes.string.isRequired,
  children: PropTypes.array,
});

export const d3treesPropTypes = PropTypes.arrayOf(d3treePropTypes);
