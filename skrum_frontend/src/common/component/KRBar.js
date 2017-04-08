import React, { Component, PropTypes } from 'react';
import _ from 'lodash';
import moment from 'moment';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';

const gap = '1em';
const margin = `${gap} ${gap} auto ${gap}`;
const style = {
  krNav: {
    display: 'flex',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpenTopLevel: {
    height: '230px',
  },
  isOpen: {
    height: '160px',
  },
  krNavBody: {
    position: 'relative',
    height: '100%',
    width: '100%',
    margin: margin,
  },
  kNavBodyInnerTopLevel: {
    height: `calc(100% - 2 * ${gap} - 52px)`,
    overflow: 'scroll',
  },
  kNavBodyInner: {
    height: `calc(100% - 2 * ${gap})`,
    overflow: 'scroll',
  },
  krNavTitle: {
  },
  krNavContent: {
    fontSize: 'x-small',
  },
  isHide: {
    display: 'none',
  },
  tools: {
    margin: margin,
    height: '100%',
  },
  toolsInner: {
    height: `calc(100% - 2 * ${gap} - 36px)`,
  },
  typeImage: {
    minWidth: '36px',
    minHeight: '36px',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  lockImage: {
    minWidth: '36px',
    minHeight: '36px',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  krNavUserImage: {
    display: 'block',
    width: '36px',
    minHeight: '36px',
    margin: 'auto',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  toolImage: {
    fontSize: 'large',
    textAlign: 'center',
    marginBottom: '0.5em',
  },
  isActive: {
    backgroundColor: '#38b6a5',
  },
  progressBoxTopLevel: {
    marginTop: '1em',
    width: '100%',
  },
  progressBox: {
    margin: margin,
    width: '280px',
  },
  progressBar: {
    height: '20px',
    width: '100%',
    border: '1px solid #fff',
  },
  progressPercent: {
    float: 'left',
    margin: '0',
    fontSize: 'medium',
    fontWeight: 'bold',
  },
  progressDivisor: {
    float: 'right',
    margin: '0',
    fontSize: 'small',
  },
  userBox: {
    margin: margin,
  },
  userName: {
    margin: '0.5em auto',
    textAlign: 'center',
    fontSize: 'large',
  },
  userInfo: {
    margin: '0 auto',
    textAlign: 'center',
    fontSize: 'x-small',
    whiteSpace: 'nowrap',
  },
};

class KRBar extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    isTopLevel: PropTypes.bool,
    title: PropTypes.string,
    content: PropTypes.string,
    dividend: PropTypes.number,
    divisor: PropTypes.number,
    isLocked: PropTypes.bool,
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
      joined: PropTypes.instanceOf(Date).isRequired,
      expiry: PropTypes.instanceOf(Date).isRequired,
    }).isRequired,
  };

  static defaultProps = {
    isOpen: true,
    isTopLevel: false,
  };

  static checkNaviOpen(isOpen, isTopLevel) {
    if (isOpen) {
      return (Object.assign({}, style.krNav, isTopLevel ? style.isOpenTopLevel : style.isOpen));
    }
    return style.krNav;
  }

  render() {
    const { isOpen, isTopLevel, title, content, dividend, divisor, isLocked, user } = this.props
    const progressPercent = _.round(dividend / divisor * 100)
    const progressBox = (boxStyle) => (
      <div style={boxStyle}>
        <progress style={style.progressBar} max={100} value={progressPercent} />
        <div>
          <div style={style.progressPercent}>{progressPercent}%</div>
          <div style={style.progressDivisor}>{dividend}単位　／　{divisor}単位</div>
        </div>
      </div>
    )
    return (
      <div style={KRBar.checkNaviOpen(isOpen, isTopLevel)}>
        <div style={style.userBox}>
          <div style={style.krNavUserImage} />
          <div style={style.userName}>{user.name}</div>
          <div style={style.userInfo}>登録日：{moment(user.joined).format('YYYY.MM.DD')}</div>
          <div style={style.userInfo}>期　限：{moment(user.expiry).format('YYYY.MM.DD')}</div>
        </div>
        <div style={style.krNavBody}>
          <div style={isTopLevel ? style.kNavBodyInnerTopLevel : style.kNavBodyInner}>
            <div style={style.krNavTitle}>{title}</div>
            <div style={style.krNavContent}>{content}</div>
          </div>
          {isTopLevel ? progressBox(style.progressBoxTopLevel) : null}
        </div>
        {!isTopLevel ? progressBox(style.progressBox) : null}
        <div style={style.tools}>
          <div style={style.toolsInner}>
            <div style={style.toolImage}>…</div>
            <div style={style.typeImage} />
          </div>
          <div style={style.lockImage} />
        </div>
      </div>
    );
  }
}

export default KRBar;
