import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import style from './UserInfoPanel.css';


/*
const gap = '1em';
const style = {
  okrNav: {
    display: 'flex',
    backgroundColor: '#fff',
    //background: `url(${imgSrc('common/bg_stripe.gif')}) #1f363e repeat 0 0`,
    overflow: 'hidden',
    color: '#626A7F',
    padding: '40px 40px 20px',
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
    //minWidth: '160px',
    width: '70px',
  },
  userImage: {
    display: 'block',
    margin: 'auto',
    width: '70px',//80px
    minHeight: '70px',//80px
    margin: 'auto',
    border: '2px solid #95989A',//#fff
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
    border: 'solid 1px #D8DFE5',
    backgroundColor: '#F0F4F5',
    color: '#8E9AA6',
  },
};
*/


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
      return `${style.okrNav} ${style.isOpen}`;
    }
    return style.okrNav;
  }

  render() {
    const { isOpen, user, infoLink } = this.props
    return (
      <div className={UserInfoPanel.checkNaviOpen(isOpen)}>
        <div className={style.userBox}>
          <div className={style.userImage} />
          <div className={style.lastUpdate}>最終更新: {moment(user.lastUpdate).fromNow()}</div>
        </div>
        <div className={style.userInfo}>
          <div className={style.userName}>{user.name}</div>
          <div className={style.userDept}>所属部署: {user.dept}</div>
          <table>
            <tbody>
              <tr className={style.userRank}>
                <td className={style.userLabel}>役 職:</td><td>{user.position}</td>
              </tr>
              <tr className={style.userPart}>
                <td className={style.userLabel}>Tel:</td><td>{user.tel}</td>
              </tr>
              <tr className={style.userPart}>
                <td className={style.userLabel}>Email:</td><td>{user.email}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <a className={style.moreLink} href={infoLink}>詳細 >></a>
      </div>
    );
  }
}

export default UserInfoPanel;
