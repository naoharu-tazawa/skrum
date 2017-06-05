import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupMemberPropTypes } from './propTypes';
import styles from './GroupMemberBar.css';

export default class GroupMemberBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    member: groupMemberPropTypes,
  };

  render() {
    const { header, member } = this.props;
    if (header) {
      return (
        <tr>
          <th className={styles.name}>名前</th>
          <th className={styles.position}>役職</th>
          <th className={styles.update}>最終ログイン</th>
          <th />
        </tr>);
    }
    const { name, position, lastLogin } = member;
    return (
      <tr>
        <td><span><img src="/img/profile/img_leader.jpg" alt="" /></span>{name}</td>
        <td>{position}</td>
        <td>{lastLogin}</td>
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);
  }
}
