import PropTypes from 'prop-types';

const replyPropTypes = PropTypes.shape({
  postId: PropTypes.number.isRequired,
  posterUserId: PropTypes.number.isRequired,
  posterUserName: PropTypes.string.isRequired,
  post: PropTypes.string.isRequired,
  postedDatetime: PropTypes.string.isRequired,
});

const autoSharePropTypes = PropTypes.shape({
  autoPost: PropTypes.string,
  okrId: PropTypes.number.isRequired,
  okrName: PropTypes.string.isRequired,
  ownerType: PropTypes.string.isRequired,
  ownerUserId: PropTypes.number,
  ownerUserName: PropTypes.string,
  ownerGroupId: PropTypes.number,
  ownerGroupName: PropTypes.string,
  ownerCompanyId: PropTypes.number,
  ownerCompanyName: PropTypes.string,
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
  post: PropTypes.string,
  postedDatetime: PropTypes.string.isRequired,
  autoShare: autoSharePropTypes,
  likesCount: PropTypes.number.isRequired,
  likedFlg: PropTypes.number,
  replies: PropTypes.arrayOf(replyPropTypes),
});

export const postsPropTypes = PropTypes.arrayOf(postPropTypes);
