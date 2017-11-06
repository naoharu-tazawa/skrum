import PropTypes from 'prop-types';
import { entityPropTypes } from '../../util/EntityUtil';

export const notePropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  type: PropTypes.string.isRequired,
  sender: entityPropTypes.isRequired,
  toNames: PropTypes.string, // .isRequired,
  lastUpdate: PropTypes.string.isRequired,
  preview: PropTypes.string.isRequired,
  read: PropTypes.bool,
});

export const notesPropTypes = PropTypes.arrayOf(notePropTypes);

export const queryPropTypes = PropTypes.shape({
  startDate: PropTypes.string.isRequired,
  endDate: PropTypes.string.isRequired,
  keyword: PropTypes.string.isRequired,
});
