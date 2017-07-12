import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link, browserHistory } from 'react-router';
import { okrPropTypes } from './propTypes';
import { replacePath, toBasicPath } from '../../util/RouteUtil';
import InlineTextArea from '../../editors/InlineTextArea';
import InlineDateInput from '../../editors/InlineDateInput';
import DeletionPrompt from '../../dialogs/DeletionPrompt';
import DropdownMenu from '../../components/DropdownMenu';
import { compareDates } from '../../util/DatetimeUtil';
import styles from './OkrDetails.css';

export default class OkrDetails extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    okr: okrPropTypes.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { parentOkr = {}, okr, dispatchPutOKR, dispatchDeleteOkr } = this.props;
    const { isDeleteOkrModalOpen = false, isDeletingOkr = false } = this.state || {};
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate,
      startDate, endDate, owner } = okr;
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
                required
                maxLength={120}
                onSubmit={(value, completion) =>
                  dispatchPutOKR(id, { okrName: value }).then(completion)}
              />
            </div>
            <div className={styles.txt}>
              <InlineTextArea
                value={detail}
                maxLength={250}
                onSubmit={(value, completion) =>
                  dispatchPutOKR(id, { okrDetail: value }).then(completion)}
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
                <span>開始日：
                  <InlineDateInput
                    value={startDate}
                    required
                    validate={value => (compareDates(value, endDate) > 0 ? '終了日は開始日以降に設定してください' : null)}
                    onSubmit={(value, completion) =>
                      dispatchPutOKR(id, { startDate: value }).then(completion)}
                  />
                </span>
                <span>期限日：
                  <InlineDateInput
                    value={endDate}
                    required
                    validate={value => (compareDates(startDate, value) > 0 ? '終了日は開始日以降に設定してください' : null)}
                    onSubmit={(value, completion) =>
                      dispatchPutOKR(id, { endDate: value }).then(completion)}
                  />
                </span>
              </div>
            </div>
            <div className={`${styles.nav_info} ${styles.cf}`}>
              <div className={`${styles.user_info} ${styles.floatL} ${styles.cf}`}>
                <div className={`${styles.avatar} ${styles.floatL}`}>
                  <img src="/img/common/icn_user.png" alt="User Name" />
                </div>
                <div className={`${styles.info} ${styles.floatL}`}>
                  <p className={styles.user_name}>{owner.name}</p>
                </div>
              </div>
              <div className={styles.member_list}>
                <DropdownMenu
                  trigger={<button><img src="/img/common/inc_link.png" alt="" width="25" /></button>}
                  options={[
                    { caption: '担当者変更' },
                    { caption: '紐付け先設定' },
                    { caption: '公開範囲設定' },
                    { caption: '削除', onClick: () => this.setState({ isDeleteOkrModalOpen: true }) },
                  ]}
                />
                <button className={styles.tool}>
                  <img src="/img/common/inc_organization.png" alt="" width="23" />
                </button>
              </div>
            </div>
          </div>
        </div>
        {isDeleteOkrModalOpen && (
          <DeletionPrompt
            title="目標の削除"
            prompt="こちらの目標を削除しますか？"
            warning={(
              <ul>
                <li>一度削除した目標は復元できません。</li>
                <li>上記の目標に直接紐づいている全ての目標/サブ目標、およびその下に紐づいている全ての目標/サブ目標も同時に削除されます。</li>
              </ul>
            )}
            onDelete={() => {
              this.setState({ isDeletingOkr: true }, () =>
                dispatchDeleteOkr(id).then(({ error }) => {
                  this.setState({ isDeletingOkr: false, isDeleteOkrModalOpen: !!error });
                  if (!error) browserHistory.push(toBasicPath());
                }),
              );
            }}
            isDeleting={isDeletingOkr}
            onClose={() => this.setState({ isDeleteOkrModalOpen: false })}
          >
            <div className={styles.deletionPrompt}>
              <div className={styles.okrToDelete}>
                <div className={`${styles.user_info} ${styles.floatL}`}>
                  <div className={`${styles.avatar} ${styles.floatL}`}>
                    <img src="/img/common/icn_user.png" alt="User Name" />
                  </div>
                  <div className={`${styles.info} ${styles.floatL}`}>
                    <p className={styles.user_name}>{owner.name}</p>
                  </div>
                </div>
                <div className={styles.okrName}>{name}</div>
              </div>
            </div>
          </DeletionPrompt>)}
      </div>);
  }
}
