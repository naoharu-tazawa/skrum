import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes, keyResultPropTypes } from './propTypes';
import { replacePath } from '../../../util/RouteUtil';
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
  krCount: {
    minWidth: `${imageDim}em`,
    textAlign: 'right',
  },
};

export default class OKRBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    okr: okrPropTypes,
    keyResult: keyResultPropTypes,
  };

  getBaseStyles = () =>
    `${styles.component} ${this.props.keyResult ? styles.keyResult : ''}`;

  render() {
    const { header, okr, keyResult } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div style={{ ...colStyle.mapImage, margin: `auto ${gap}em` }}>MAP</div>
          <div style={{ ...colStyle.name, margin: 'auto 0' }}>OKR</div>
          <div style={{ ...colStyle.progressBar, margin: `auto ${gap}em auto 0` }}>進捗</div>
          <div style={{ ...colStyle.ownerBox, margin: 'auto 0' }}>所有者</div>
          <div style={{ ...colStyle.krCount, margin: `auto ${gap}em auto 0` }}>KR</div>
        </div>);
    }
    const { id, name, achievementRate, owner, keyResults } = okr || keyResult;
    return (
      <div className={this.getBaseStyles()}>
        <div className={styles.mapImage} style={colStyle.mapImage} />
        <div className={styles.name} style={colStyle.name}>
          {keyResult ? <span className={styles.keyResultConnector}>└</span> : null}
          <Link to={replacePath({ subSection: 'o', subId: `${id}` })} className={styles.okrDetailsLink}>
            {name}
          </Link>
        </div>
        <div className={styles.progressBox} style={colStyle.progressBar}>
          <div className={styles.progressPercent}>{achievementRate}%</div>
          <progress className={styles.progressBar} max={100} value={achievementRate} />
        </div>
        <div className={styles.ownerBox} style={colStyle.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
        <div className={styles.krCount} style={colStyle.krCount}>{keyResults ? keyResults.length : ''}</div>
      </div>);
  }
}
