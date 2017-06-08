import React, { Component } from 'react';
import { okrPropTypes } from './propTypes';
import InlineTextInput from '../../editors/InlineTextInput';
import InlineTextArea from '../../editors/InlineTextArea';
import styles from './OKRDetails.css';

export default class OKRDetails extends Component {

  static propTypes = {
    okr: okrPropTypes,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { okr } = this.props;
    const { name, detail, unit, targetValue, achievedValue, achievementRate, owner } = okr;
    return (
      <div>
        <div className={`${styles.content} ${styles.txt_top} ${styles.cf}`}>
          <p className={styles.alignment}>紐付け先目標</p>
          <div className={`${styles.txt_content_top} ${styles.floatL}`}>
              こちらは上位OKRが入りますこちらは上位 こちらは上位OKRが入りますこちらは上位 こちらは上位OKRが入りますこちらは上位
              こちらは上位OKRが入りますこちらは上位
              こちらは上位OKRが。。。
          </div>
          <div className={`${styles.img_content_top} ${styles.floatL}`}>
            <img src="/img/common/icn_user.png" alt="User Name" />
            <span>User Name</span>
          </div>
        </div>
        <div className={`${styles.content} ${styles.cf}`}>
          <div className={styles.boxInfo}>
            <p className={styles.ttl_team}>
              <InlineTextInput value={name} />
            </p>
            <div className={styles.txt}>
              <InlineTextArea value={detail} />
            </div>
            <div className={`${styles.bar_top} ${styles.cf}`}>
              <div className={styles.progressBox}>
                <div className={styles.progressPercent}>{achievementRate}%</div>
                <div className={styles.progressBar}>
                  <div
                    className={this.getProgressStyles(achievementRate)}
                    style={{ width: `${achievementRate}%` }}
                  />
                </div>
              </div>
            </div>
            <div className={`${styles.bar_top_bottom} ${styles.cf}`}>
              <div className={`${styles.txt_percent} ${styles.floatL}`}>
                {achievedValue}／{targetValue}{unit}
              </div>
              <div className={`${styles.txt_date} ${styles.floatR}`}>開始日：2017/01/01 期限日：2017/01/01</div>
            </div>
            <div className={`${styles.nav_info} ${styles.cf}`}>
              <div className={`${styles.user_info} ${styles.floatL} ${styles.cf}`}>
                <div className={`${styles.avatar} ${styles.floatL}`}><img src="/img/common/icn_user.png" alt="User Name" /></div>
                <div className={`${styles.info} ${styles.floatL}`}>
                  <p className={styles.user_name}>{owner.name}</p>
                </div>
              </div>
              <div className={styles.member_list}>
                <button className={styles.hover}><img src="/img/common/inc_link.png" alt="" width="25" /></button>
                <button className={styles.hover}><img src="/img/common/inc_organization.png" alt="" width="23" /></button>
              </div>
            </div>
          </div>
        </div>
      </div>);
  }
}
