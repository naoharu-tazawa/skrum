import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes } from './propTypes';
import { replacePath } from '../../util/RouteUtil';
import InlineTextArea from '../../editors/InlineTextArea';
import styles from './OkrDetails.css';

export default class OkrDetails extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    okr: okrPropTypes.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { parentOkr = {}, okr, dispatchPutOKR } = this.props;
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate, owner } = okr;
    return (
      <div>
        <div className={`${styles.content} ${styles.txt_top} ${styles.cf}`}>
          <p className={styles.alignment}>紐付け先目標</p>
          <div className={`${styles.txt_content_top} ${styles.floatL}`}>
            {parentOkr && (
              <Link to={replacePath({ aspect: 'o', aspectId: `${parentOkr.id}` })}>
                {parentOkr.name}
              </Link>)}
            {!parentOkr && <span>➖</span>}
          </div>
          {parentOkr && (
            <div className={`${styles.img_content_top} ${styles.floatL}`}>
              <img src="/img/common/icn_user.png" alt="User Name" />
              <span>{parentOkr.owner.name}</span>
            </div>)}
        </div>
        <div className={`${styles.content} ${styles.cf}`}>
          <div className={styles.boxInfo}>
            <div className={styles.ttl_team}>
              <InlineTextArea
                value={name}
                onSubmit={value => dispatchPutOKR(id, { okrName: value })}
              />
            </div>
            <div className={styles.txt}>
              <InlineTextArea
                value={detail}
                onSubmit={value => dispatchPutOKR(id, { okrDetail: value })}
              />
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
              <div className={`${styles.txt_date} ${styles.floatR}`}>
                <span>開始日：<span>2017/01/01</span></span>
                <span>期限日：<span>2017/03/31</span></span>
              </div>
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
