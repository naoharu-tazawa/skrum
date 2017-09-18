import PropTypes from 'prop-types';

const entityPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  name: PropTypes.string.isRequired,
  type: PropTypes.string.isRequired,
  roleLevel: PropTypes.number,
  groupType: PropTypes.string,
});

const replyPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  poster: entityPropTypes.isRequired,
  post: PropTypes.string.isRequired,
  postedDatetime: PropTypes.string.isRequired,
});

const autoSharePropTypes = PropTypes.shape({
  autoPost: PropTypes.string,
  okrId: PropTypes.number.isRequired,
  okrName: PropTypes.string.isRequired,
  owner: entityPropTypes.isRequired,
});

export const postPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  post: PropTypes.string,
  poster: entityPropTypes.isRequired,
  postedDatetime: PropTypes.string.isRequired,
  autoShare: autoSharePropTypes,
  likesCount: PropTypes.number.isRequired,
  likedFlg: PropTypes.number,
  replies: PropTypes.arrayOf(replyPropTypes),
});

export const postsPropTypes = PropTypes.arrayOf(postPropTypes);
