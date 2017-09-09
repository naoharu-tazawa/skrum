import React, { Component } from 'react';
import { companyPropTypes } from './propTypes';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import { toRelativeTimeText } from '../../../util/DatetimeUtil';
import styles from './CompanyInfo.css';

export default class CompanyInfo extends Component {

  static propTypes = {
    company: companyPropTypes.isRequired,
  };

  render() {
    const { company } = this.props;
    const { companyId, name, mission, vision, lastUpdate } = company;
    return (
      <div className={`${styles.content} ${styles.cf}`}>
        <figure className={styles.floatL}>
          <EntityLink entity={{ id: companyId, type: EntityType.COMPANY }} fluid avatarOnly />
          <figcaption className={styles.alignC}>
            最終更新:<br />{toRelativeTimeText(lastUpdate)}
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
