import React, { Component, PropTypes } from 'react';
import moment from 'moment';
import { Link } from 'react-router';
import { cssClass } from '../../util/StyleUtil';
import { imgSrc } from '../../util/ResourceUtil';
import style from './UserInfoPanel.css';

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
