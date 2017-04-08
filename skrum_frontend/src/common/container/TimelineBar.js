import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import PostBar from '../component/PostBar';
import CommentBar from '../component/CommentBar';

const gap = '1em';

const style = {
  okrNav: {
    display: 'block',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
  },
  isHide: {
    display: 'none',
  },
  postBar: {
    display: 'flex',
    borderWidth: 'thin',
    borderStyle: 'solid',
  },
  commentBars: {
    overflowY: 'auto',
    borderWidth: 'thin',
  },
  commentBar: {
    borderWidth: 'thin',
    borderLeftStyle: 'solid',
    borderRightStyle: 'solid',
    borderBottomStyle: 'solid',
  },
  actionBar: {
    padding: '0 ' + gap,
    borderWidth: 'thin',
    borderLeftStyle: 'solid',
    borderRightStyle: 'solid',
    borderBottomStyle: 'solid',
  },
  flatButton: {
    border: 'none',
    background: 'none',
    fontSize: 'medium',
    color: 'white',
  },
  newCommentBar: {
    marginTop: gap,
    padding: gap,
    borderWidth: 'thin',
    borderStyle: 'solid',
    whiteSpace: 'nowrap',
  },
  commentInput: {
    width: '100%',
    fontSize: 'large',
  },
  newCommentBox: {
    display: 'flex',
  },
  disabledButton: {
    color: 'lightgray',
  },
};

class TimelineBar extends Component {
  state = {
    showNewCommentBar: false,
    newComment: '',
  }

  static propTypes = {
    isOpen: PropTypes.bool,
    post: PropTypes.string.isRequired,
    date: PropTypes.instanceOf(Date),
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
    comments: PropTypes.arrayOf(PropTypes.shape({
      post: PropTypes.string.isRequired,
      date: PropTypes.instanceOf(Date),
      user: PropTypes.shape({
        name: PropTypes.string.isRequired,
      }),
    })),
    handleLike: PropTypes.func.isRequired,
    handleAddComment: PropTypes.func.isRequired,
  };

  static defaultProps = {
    isOpen: true,
    comments: [],
  };

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  onCommentChange(event) {
    this.setState({ newComment: event.target.value })
  }

  toggleAddComment() {
    this.setState({ showNewCommentBar: !this.state.showNewCommentBar })
  }

  render() {
    const { isOpen, post, date, user, comments, handleLike, handleAddComment } = this.props
    const { showNewCommentBar, newComment } = this.state
    const isAddCommentDisabled = _.isEmpty(newComment)
    const submitButtonStyle = _.defaults(isAddCommentDisabled ? style.disabledButton : {}, style.flatButton)
    return (
      <div style={TimelineBar.checkNaviOpen(isOpen)}>
        <div style={style.postBar}>
          <PostBar {...{post, date, user}} />
        </div>
        <div style={style.commentBars}>
          {comments.map((comment, index) =>
            <div key={index} style={style.commentBar}>
              <CommentBar {...comment} />
            </div>)}
        </div>
        <div style={style.actionBar}>
          <button name='like' style={style.flatButton} onClick={() => handleLike()}>いいね</button>
          |
          <button name='comment' style={style.flatButton} onClick={this.toggleAddComment.bind(this)}>コメントする</button>
        </div>
        {!showNewCommentBar ? null : (
          <div style={style.newCommentBar}>
            <div>コメント</div>
            <div style={style.newCommentBox}>
              <input type='text' name='new-comment' placeholder=''
                     onChange={this.onCommentChange.bind(this)} style={style.commentInput} />
              <button name='add-comment' style={submitButtonStyle} disabled={_.isEmpty(newComment)}
                onClick={() => { handleAddComment(newComment); this.toggleAddComment() }}>
                送信
              </button>
            </div>
          </div>
        )}
      </div>
    );
  }
}

export default TimelineBar;
