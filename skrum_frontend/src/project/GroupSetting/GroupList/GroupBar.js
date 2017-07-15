import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupPropTypes } from './propTypes';
import styles from './GroupBar.css';

export default class GroupBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    group: groupPropTypes,
  };

  render() {
    const { header, group } = this.props;
    if (header) {
      return (
        <tr>
          <th className={styles.name}>名前</th>
          <th className={styles.position}>グループ種別</th>
          <th className={styles.update}>最終ログイン</th>
          <th />
        </tr>);
    }
    const { name } = group;
    return (
      <tr>
        <td><span><img src="/img/profile/img_leader.jpg" alt="" /></span>{name}</td>
        <td />
        <td />
        <td><div className={styles.delete}><img src="/img/delete.svg" alt="" /></div></td>
      </tr>);
  }
}
