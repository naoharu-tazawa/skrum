import PropTypes from 'prop-types';

const replyPropTypes = PropTypes.shape({
  postId: PropTypes.number.isRequired,
  posterId: PropTypes.number.isRequired,
  posterName: PropTypes.string.isRequired,
  post: PropTypes.string.isRequired,
  postedDatetime: PropTypes.string.isRequired,
});

export const postPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  posterId: PropTypes.number.isRequired,
  posterName: PropTypes.string.isRequired,
  post: PropTypes.string.isRequired,
  postedDatetime: PropTypes.string.isRequired,
  okrId: PropTypes.number,
  likesCount: PropTypes.number.isRequired,
  likedFlg: PropTypes.number,
  replies: PropTypes.arrayOf(replyPropTypes),
});

export const postsPropTypes = PropTypes.arrayOf(postPropTypes);
