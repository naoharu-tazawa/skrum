import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { okrPropTypes } from './propTypes';
import styles from './OKRBar.css';

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

export default class OKRBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    okr: okrPropTypes.isRequired,
  };

  render() {
    const { header, okr } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div style={{ ...colStyle.mapImage, margin: `auto ${gap}em` }}>MAP</div>
          <div style={{ ...colStyle.name, margin: 'auto 0' }}>OKR</div>
          <div style={{ ...colStyle.progressBar, margin: `auto ${gap}em auto 0` }}>進捗</div>
          <div style={{ ...colStyle.ownerBox, margin: 'auto 0' }}>所有者</div>
          <div style={{ ...colStyle.tool, margin: `auto ${gap}em auto 0` }} />
        </div>);
    }
    const { name, achievementRate, owner } = okr;
    return (
      <div className={styles.component}>
        <div className={styles.mapImage} style={colStyle.mapImage} />
        <div className={styles.name} style={colStyle.name}>
          {name}
        </div>
        <div className={styles.progressBox} style={colStyle.progressBar}>
          <div className={styles.progressPercent}>{achievementRate}%</div>
          <progress className={styles.progressBar} max={100} value={achievementRate} />
        </div>
        <div className={styles.ownerBox} style={colStyle.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
        <div className={styles.toolImage} style={colStyle.tool} />
      </div>);
  }
}
