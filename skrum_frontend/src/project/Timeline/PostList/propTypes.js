import PropTypes from 'prop-types';

const replyPropTypes = PropTypes.shape({
  postId: PropTypes.number.isRequired,
  posterUserId: PropTypes.number.isRequired,
  posterUserName: PropTypes.string.isRequired,
  post: PropTypes.string.isRequired,
  postedDatetime: PropTypes.string.isRequired,
});

export const postPropTypes = PropTypes.shape({
  id: PropTypes.number.isRequired,
  posterType: PropTypes.string.isRequired,
  posterUserId: PropTypes.number,
  posterUserName: PropTypes.string,
  posterGroupId: PropTypes.number,
  posterGroupName: PropTypes.string,
  posterCompanyId: PropTypes.number,
  posterCompanyName: PropTypes.string,
  post: PropTypes.string.isRequired,
  postedDatetime: PropTypes.string.isRequired,
  okrId: PropTypes.number,
  likesCount: PropTypes.number.isRequired,
  likedFlg: PropTypes.number,
  replies: PropTypes.arrayOf(replyPropTypes),
});

export const postsPropTypes = PropTypes.arrayOf(postPropTypes);
