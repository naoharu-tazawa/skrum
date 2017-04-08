import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import OKRBar from '../component/OKRBar';

const gap = '1em';
const headerHeight = '2em';

const style = {
  okrNav: {
    display: 'block',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
    height: '100%',
  },
  isHide: {
    display: 'none',
  },
  okrHeader: {
    display: 'flex',
    height: headerHeight,
    borderWidth: 'thin',
    borderStyle: 'solid',
    position: 'relative',
    zIndex: 2,
  },
  okrHeaderInner: {
    display: 'flex',
    width: '100%',
    margin: 'auto 0',
  },
  okrBars: {
    height: `calc(100% - ${headerHeight} - 2px)`,
    overflowY: 'auto',
    borderWidth: 'thin',
    borderLeftStyle: 'solid',
    borderRightStyle: 'solid',
    borderBottomStyle: 'solid',
    position: 'relative',
    top: '-1px',
  },
  okrBar: {
    borderWidth: 'thin',
    borderTopStyle: 'solid',
  },
};

class OKRList extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    list: PropTypes.arrayOf(PropTypes.shape({
      title: PropTypes.string.isRequired,
      isRoot: PropTypes.bool.isRequired,
      progress: PropTypes.number.isRequired,
      owner: PropTypes.shape({
        name: PropTypes.string.isRequired,
      }).isRequired,
    })),
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
    const { isOpen, list } = this.props
    return (
      <div style={OKRList.checkNaviOpen(isOpen)}>
        <div style={style.okrHeader}>
          <div style={style.okrHeaderInner}>
            {['', 'OKR', '進捗', '所有者', '', ''].map((header, index) =>
              <div key={index} style={OKRBar.columnWidthsStyle[index]}>{header}</div>
            )}
          </div>
        </div>
        <div style={style.okrBars}>
          {list.map((okr, index) =>
            <div key={index} style={style.okrBar}>
              <OKRBar {...okr} />
            </div>)}
        </div>
      </div>
    );
  }
}

export default OKRList;
