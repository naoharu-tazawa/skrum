import React, { Component } from 'react';
import { companyPropTypes } from './propTypes';
import styles from './CompanyInfo.css';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';

export default class CompanyInfo extends Component {

  static propTypes = {
    company: companyPropTypes.isRequired,
  };

  render() {
    const { company } = this.props;
    const { name, mission, vision, lastUpdate } = company;
    return (
      <div className={`${styles.content} ${styles.cf}`}>
        <figure className={`${styles.avatar} ${styles.floatL}`}>
          <img src="/img/time_management/icn_team.png" alt="Company Name" />
          <figcaption className={styles.alignC}>
            最終更新: {toRelativeTimeText(lastUpdate)}
          </figcaption>
        </figure>
        <div className={`${styles.boxInfo} ${styles.floatR}`}>
          <p className={styles.ttl_company}>{name}</p>
          <div className={styles.companyPart}>
            <div>
              <div className={styles.title}>ヴィジョン</div>
              <div className={styles.data}>{vision}</div>
            </div>
            <div>
              <div className={styles.title}>ミッション</div>
              <div className={styles.data}>{mission}</div>
            </div>
          </div>
        </div>
      </div>
    );
  }
}
