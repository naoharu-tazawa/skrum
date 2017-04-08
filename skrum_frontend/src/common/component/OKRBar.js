import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';

const gap = 1
const gapEm = gap + 'em'
const imageDim = 2.25
const remainingWidth = '100%'
const progressBarWidth = 10
const ownerBoxWidth = 5
const dropdownWidth = 5

const style = {
  okrNav: {
    display: 'flex',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
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
  typeImage: {
    display: 'inline-block',
    minWidth: imageDim + 'em',
    minHeight: imageDim + 'em',
    margin: 'auto 0 auto ' + gapEm,
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  title: {
    position: 'relative',
    width: remainingWidth,
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    margin: 'auto ' + gapEm,
  },
  progressBox: {
    margin: 'auto ' + gapEm,
  },
  progressBar: {
    height: '20px',
    border: '1px solid #fff',
    width: progressBarWidth + 'em',
  },
  progressLabel: {
    float: 'left',
    margin: '0',
  },
  progressPercent: {
    float: 'right',
    margin: '0',
  },
  ownerBox: {
    margin: 'auto 0',
    minWidth: ownerBoxWidth + 'em',
    maxWidth: ownerBoxWidth + 'em',
  },
  ownerImage: {
    display: 'block',
    width: imageDim + 'em',
    minHeight: imageDim + 'em',
    margin: 'auto',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  ownerName: {
    margin: '0 auto',
    textAlign: 'center',
    whiteSpace: 'nowrap',
    overflow: 'hidden',
    textOverflow: 'ellipsis',
    fontSize: 'smaller',
  },
  toolImage: {
    display: 'inline-block',
    minWidth: imageDim + 'em',
    minHeight: imageDim + 'em',
    margin: 'auto 0 auto ' + gapEm,
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  dropdown: {
    backgroundImage: 'linear-gradient(45deg, transparent 50%, gray 50%), linear-gradient(135deg, gray 50%, transparent 50%)',
    backgroundPosition: 'calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px), calc(100% - 2.5em) 0.5em',
    backgroundSize: '5px 5px, 5px 5px, 1px 1.5em',
    backgroundRepeat: 'no-repeat',
    width: dropdownWidth + 'em',
    marginTop: '10px',
  },
};

class OKRBar extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    title: PropTypes.string.isRequired,
    isRoot: PropTypes.bool.isRequired,
    progress: PropTypes.number,
    owner: PropTypes.shape({
      name: PropTypes.string.isRequired,
    }).isRequired,
  };

  static defaultProps = {
    isOpen: true,
  };

  static columnWidthsStyle = [
    { minWidth: (gap + imageDim + gap) + 'em' },
    { width: remainingWidth },
    { minWidth: (gap + progressBarWidth + gap) + 'em' },
    { minWidth: ownerBoxWidth + 'em' },
    { minWidth: (gap + imageDim) + 'em' },
    { minWidth: gapEm },
  ]

  static checkNaviOpen(isOpen) {
    if (isOpen) {
      return (Object.assign({}, style.okrNav, style.isOpen));
    }
    return style.okrNav;
  }

  render() {
    const { isOpen, title, progress, owner } = this.props
    return (
      <div style={OKRBar.checkNaviOpen(isOpen)}>
        <div style={style.typeImage} />
        <div style={style.title}>
          {title}
        </div>
        <div style={style.progressBox}>
          <progress style={style.progressBar} max={100} value={progress} />
          <div>
            <div style={style.progressLabel}>Progress</div>
            <div style={style.progressPercent}>{progress}%</div>
          </div>
        </div>
        <div style={style.ownerBox}>
          <div style={style.ownerImage} />
          <div style={style.ownerName}>{owner.name}</div>
        </div>
        <div style={style.toolImage} />
        <div style={style.dropdown} />
      </div>
    );
  }
}

export default OKRBar;
