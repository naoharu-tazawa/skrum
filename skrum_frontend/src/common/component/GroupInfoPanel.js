import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';

const gap = '1em';
const style = {
  okrNav: {
    display: 'flex',
    background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#fff',
  },
  isOpen: {
    position: 'relative',
    height: '160px',
  },
  isHide: {
    display: 'none',
  },
  isActive: {
    backgroundColor: '#38b6a5',
  },
  groupBox: {
    margin: 'auto 0',
    minWidth: '160px',
  },
  groupImage: {
    display: 'block',
    margin: 'auto',
    width: '80px',
    minHeight: '80px',
    margin: 'auto',
    border: '2px solid #fff',
    borderRadius: '50%',
    overflow: 'hidden',
    boxSizing: 'border-box',
  },
  lastUpdate: {
    fontSize: 'x-small',
    textAlign: 'center',
    margin: '0.5em 0',
  },
  groupInfo: {
    margin: `${gap} ${gap} 0 0`,
    //borderSpacing: 0,
  },
  groupName: {
    fontSize: 'x-large',
    marginBottom: '0.5em',
  },
  groupDept: {
    fontSize: 'x-small',
    color: 'darkgray',
  },
  groupGoal: {
    fontSize: 'x-small',
    margin: '0.5em 0',
    verticalAlign: 'top',
  },
  groupPart: {
    fontSize: 'x-small',
    verticalAlign: 'top',
  },
  groupLabel: {
    whiteSpace: 'nowrap',
    paddingRight: gap,
  },
  moreLink: {
    fontSize: 'small',
    position: 'absolute',
    alignSelf: 'flex-end',
    right: 0,
    padding: `0 ${gap} ${gap} 0`,
  },
};

class GroupInfoPanel extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    group: PropTypes.shape({
      name: PropTypes.string.isRequired,
      company: PropTypes.string.isRequired,
      dept: PropTypes.string.isRequired,
      mission: PropTypes.string.isRequired,
      leader: PropTypes.string,
      lastUpdate: PropTypes.instanceOf(Date),
    }).isRequired,
    infoLink: PropTypes.string.isRequired,
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
    const { isOpen, group, infoLink } = this.props
    return (
      <div style={GroupInfoPanel.checkNaviOpen(isOpen)}>
        <div style={style.groupBox}>
          <div style={style.groupImage} />
          <div style={style.lastUpdate}>最終更新: {moment(group.lastUpdate).fromNow()}</div>
        </div>
        <div style={style.groupInfo}>
          <div style={style.groupName}>{group.name}</div>
          <div style={style.groupDept}>{group.company} > {group.dept}</div>
          <table>
            <tbody>
              <tr style={style.groupGoal}>
                <td style={style.groupLabel}>ミッション</td><td>{group.mission}</td>
              </tr>
              <tr style={style.groupPart}>
                <td style={style.groupLabel}>リーダー</td><td>{group.leader}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <a style={style.moreLink} href={infoLink}>メンバー一覧 >></a>
      </div>
    );
  }
}

export default GroupInfoPanel;
