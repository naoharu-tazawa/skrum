import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { companyPropTypes } from './propTypes';
import styles from './CompanyInfo.css';
import { convertToRelativeTimeText } from '../../../util/DatetimeUtil';

export default class CompanyInfo extends Component {

  static propTypes = {
    company: companyPropTypes.isRequired,
    infoLink: PropTypes.string.isRequired,
  };

  render() {
    const { company, infoLink } = this.props;
    const { name, mission, vision, lastUpdate } = company;
    return (
      <div className={styles.component}>
        <div className={styles.companyBox}>
          <div className={styles.companyImage} />
          <div className={styles.lastUpdate}>最終更新: {convertToRelativeTimeText(lastUpdate)}</div>
        </div>
        <div className={styles.companyInfo}>
          <div className={styles.companyName}>{name}</div>
          <div className={styles.companyGoal}>{mission}</div>
          <div className={styles.companyPart}>
            <div className={styles.companyVision}>
              <div>ビジョン</div>
              <div>{vision}</div>
            </div>
            <a className={styles.moreLink} href={infoLink}>メンバー一覧 ➔</a>
          </div>
        </div>
      </div>
    );
  }
}
