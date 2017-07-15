import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { userPropTypes } from './propTypes';
import styles from './UserBar.css';

export default class UserBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    user: userPropTypes,
  };

  render() {
    const { header, user } = this.props;
    if (header) {
      return (
        <tr>
          <th className={styles.name}>名前</th>
          <th className={styles.position}>権限</th>
          <th className={styles.update}>最終ログイン</th>
          <th />
        </tr>);
    }
    const { name, roleAssignmentId, roleAssignmentName, lastLogin } = user;
    return (
      <tr>
        <td><span><img src="/img/profile/img_leader.jpg" alt="" /></span>{name}</td>
        <td>{roleAssignmentId}{roleAssignmentName}</td>
        <td>{lastLogin}</td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);
  }
}
