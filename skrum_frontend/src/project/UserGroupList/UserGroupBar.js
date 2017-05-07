import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { userGroupPropTypes } from './propTypes';
import styles from './UserGroupBar.css';

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

export default class UserGroupBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    group: userGroupPropTypes,
  };

  render() {
    const { header, group } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div style={{ ...colStyle.name, margin: 'auto 0' }}>名前</div>
          <div style={{ ...colStyle.progressBar, margin: `auto ${gap}em auto 0` }}>進捗状況</div>
          <div style={{ ...colStyle.deleteButton, margin: `auto ${gap}em auto 0` }} />
        </div>);
    }
    const { name, achievementRate } = group;
    return (
      <div className={styles.component}>
        <div className={styles.name} style={colStyle.name}>
          {name}
        </div>
        <div className={styles.progressBox} style={colStyle.progressBar}>
          <div className={styles.progressPercent}>{achievementRate}%</div>
          <progress className={styles.progressBar} max={100} value={achievementRate} />
        </div>
        <div className={styles.name} style={colStyle.name}>×</div>
      </div>);
  }
}
