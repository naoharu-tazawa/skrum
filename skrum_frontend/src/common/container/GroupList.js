import React, { Component, PropTypes } from 'react';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import GroupBar from '../component/GroupBar';

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
  groupHeader: {
    display: 'flex',
    height: headerHeight,
    borderWidth: 'thin',
    borderStyle: 'solid',
    position: 'relative',
    zIndex: 2,
  },
  groupHeaderInner: {
    display: 'flex',
    width: '100%',
    margin: 'auto 0',
  },
  groupBars: {
    height: `calc(100% - ${headerHeight} - 2px)`,
    overflowY: 'auto',
    borderWidth: 'thin',
    borderLeftStyle: 'solid',
    borderRightStyle: 'solid',
    borderBottomStyle: 'solid',
    position: 'relative',
    top: '-1px',
  },
  groupBar: {
    borderWidth: 'thin',
    borderTopStyle: 'solid',
  },
};

class GroupList extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    list: PropTypes.arrayOf(PropTypes.shape({
      name: PropTypes.string.isRequired,
      progress: PropTypes.number.isRequired,
      lastUpdate: PropTypes.instanceOf(Date),
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
      <div style={GroupList.checkNaviOpen(isOpen)}>
        <div style={style.groupHeader}>
          <div style={style.groupHeaderInner}>
            {['', '名前', '最終更新日', '進捗状況', ''].map((header, index) =>
              <div key={index} style={GroupBar.columnWidthsStyle[index]}>{header}</div>
            )}
          </div>
        </div>
        <div style={style.groupBars}>
          {list.map((group, index) =>
            <div key={index} style={style.groupBar}>
              <GroupBar {...group} />
            </div>)}
        </div>
      </div>
    );
  }
}

export default GroupList;
