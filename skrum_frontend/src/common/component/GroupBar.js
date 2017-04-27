import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import style from './GroupBar.css';

/*
const gap = 1
const gapEm = gap + 'em'
const imageDim = 2.25
const groupNameWidth = 8
const lastUpdateWidth = 6
const progressPercentWidth = 1
const progressBarWidth = 10
const remainingWidth = '100%'

const style = {
  okrNav: {
    display: 'flex',
    backgroundColor: '#fff',
    //background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#626A7F',
  },
  isOpen: {
    height: '60px',
  },
  isHide: {
    display: 'none',
  },
  isActive: {
    backgroundColor: '#38b6a5',
  },
  groupImage: {
    display: 'inline-block',
    minWidth: imageDim + 'em',
    minHeight: imageDim + 'em',
    margin: 'auto 0 auto ' + gapEm,
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  groupName: {
    margin: 'auto ' + gapEm,
    minWidth: groupNameWidth + 'em',
    maxWidth: groupNameWidth + 'em',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
  },
  lastUpdate: {
    margin: 'auto ' + gapEm,
    minWidth: lastUpdateWidth + 'em',
    maxWidth: lastUpdateWidth + 'em',
  },
  progressPercent: {
    margin: 'auto ' + gapEm,
    width: progressPercentWidth + 'em',
  },
  progressBar: {
    margin: 'auto ' + gapEm,
    height: '20px',
    minWidth: progressBarWidth + 'em',
    border: '1px solid #fff',
  },
  deleteTool: {
    margin: `auto ${gapEm} auto auto`,
    width: remainingWidth,
    textAlign: 'right',
  },
};
*/

class GroupBar extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    name: PropTypes.string.isRequired,
    lastUpdate: PropTypes.instanceOf(Date),
    progress: PropTypes.number,
  };

  static defaultProps = {
    isOpen: true,
  };

  static columnWidthsStyle = [
    { minWidth: (gap + imageDim + gap) + 'em' },
    { minWidth: (gap + groupNameWidth + gap) + 'em' },
    { minWidth: (gap + lastUpdateWidth + gap) + 'em' },
    { minWidth: (gap + progressPercentWidth + progressBarWidth + gap) + 'em' },
    { width: remainingWidth },
  ]

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  render() {
    const { isOpen, progress, name, lastUpdate } = this.props
    return (
      <div style={GroupBar.checkNaviOpen(isOpen)}>
        <div style={style.groupImage} />
        <div style={style.groupName}>{name}</div>
        <div style={style.lastUpdate}>{lastUpdate ? moment(lastUpdate).fromNow() : ''}</div>
        <div style={style.progressPercent}>{progress}%</div>
        <progress style={style.progressBar} max={100} value={progress} />
        <div style={style.deleteTool}>×</div>
      </div>
    );
  }
}

export default GroupBar;
