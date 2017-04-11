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
  userBox: {
    margin: 'auto 0',
    minWidth: '160px',
  },
  userImage: {
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
  userInfo: {
    margin: `${gap} ${gap} 0 0`,
    //borderSpacing: 0,
  },
  userName: {
    fontSize: 'x-large',
    marginBottom: '0.5em',
  },
  userDept: {
    fontSize: 'x-small',
    color: 'darkgray',
  },
  userRank: {
    fontSize: 'small',
    margin: '0.5em 0',
    verticalAlign: 'top',
  },
  userPart: {
    fontSize: 'x-small',
    verticalAlign: 'top',
  },
  userLabel: {
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

class UserInfoPanel extends Component {

  static propTypes = {
    isOpen: PropTypes.bool,
    user: PropTypes.shape({
      name: PropTypes.string.isRequired,
      dept: PropTypes.string.isRequired,
      position: PropTypes.string.isRequired,
      tel: PropTypes.string,
      email: PropTypes.string,
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
    const { isOpen, user, infoLink } = this.props
    return (
      <div style={UserInfoPanel.checkNaviOpen(isOpen)}>
        <div style={style.userBox}>
          <div style={style.userImage} />
          <div style={style.lastUpdate}>最終更新: {moment(user.lastUpdate).fromNow()}</div>
        </div>
        <div style={style.userInfo}>
          <div style={style.userName}>{user.name}</div>
          <div style={style.userDept}>所属部署: {user.dept}</div>
          <table>
            <tbody>
              <tr style={style.userRank}>
                <td style={style.userLabel}>役 職:</td><td>{user.position}</td>
              </tr>
              <tr style={style.userPart}>
                <td style={style.userLabel}>Tel:</td><td>{user.tel}</td>
              </tr>
              <tr style={style.userPart}>
                <td style={style.userLabel}>Email:</td><td>{user.email}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <a style={style.moreLink} href={infoLink}>詳細 >></a>
      </div>
    );
  }
}

export default UserInfoPanel;