import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { groupMemberPropTypes } from './propTypes';
import styles from './GroupMemberBar.css';

const gap = 1;
const imageDim = 2.25;
const progressBarWidth = 10;
const ownerBoxWidth = 10;

const colStyle = {
  mapImage: {
    minWidth: `${imageDim}em`,
//    height: `${imageDim}em`,
  },
  name: {
    width: '100%',
  },
  progressBar: {
    minWidth: `${progressBarWidth}em`,
  },
  ownerBox: {
    minWidth: `${ownerBoxWidth}em`,
  },
  tool: {
    minWidth: `${imageDim}em`,
    height: `${imageDim}em`,
  },
};

export default class GroupMemberBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    member: groupMemberPropTypes,
  };

  render() {
    const { header, member } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div style={{ ...colStyle.name, margin: 'auto 0' }}>名前</div>
          <div style={{ ...colStyle.progressBar, margin: `auto ${gap}em auto 0` }}>役職</div>
          <div style={{ ...colStyle.progressBar, margin: `auto ${gap}em auto 0` }}>最終ログイン日時</div>
          <div style={{ ...colStyle.deleteButton, margin: `auto ${gap}em auto 0` }} />
        </div>);
    }
    const { name, position, lastLogin } = member;
    return (
      <div className={styles.component}>
        <div className={styles.name} style={colStyle.name}>{name}</div>
        <div className={styles.name} style={colStyle.name}>{position}</div>
        <div className={styles.name} style={colStyle.name}>{lastLogin}</div>
        <div className={styles.name} style={colStyle.name}>×</div>
      </div>);
  }
}
