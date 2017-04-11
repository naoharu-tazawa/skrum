import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';

const gap = 1
const gapEm = gap + 'em'
const imageDim = 2.25
const remainingWidth = '100%'

const style = {
  okrNav: {
    display: 'flex',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
    width: '100%',
    padding: '0.5em 0',
  },
  isHide: {
    display: 'none',
  },
  isActive: {
    backgroundColor: '#38b6a5',
  },
  postImage: {
    display: 'inline-block',
    minWidth: imageDim + 'em',
    minHeight: imageDim + 'em',
    margin: 'auto 4em auto ' + gapEm,
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  post: {
    margin: 'auto 0',
    width: remainingWidth,
  },
  date: {
    margin: `auto ${gapEm} auto auto`,
    whiteSpace: 'nowrap',
  },
};

class PostBar extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    post: PropTypes.string.isRequired,
    date: PropTypes.instanceOf(Date),
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  render() {
    const { isOpen, post, date, user } = this.props
    return (
      <div style={PostBar.checkNaviOpen(isOpen)}>
        <div style={style.postImage} />
        <div style={style.post}>{post}</div>
        <div style={style.date}>{moment(date).format("M/D HH:mm")}</div>
      </div>
    );
  }
}

export default PostBar;