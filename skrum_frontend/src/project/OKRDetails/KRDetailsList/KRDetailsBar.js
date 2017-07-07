import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { keyResultPropTypes } from '../propTypes';
import InlineTextArea from '../../../editors/InlineTextArea';
import InlineTextInput from '../../../editors/InlineTextInput';
import DeletionPrompt from '../../../dialogs/DeletionPrompt';
import DropdownMenu from '../../../components/DropdownMenu';
import styles from './KRDetailsBar.css';

export default class KRDetailsBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    keyResult: keyResultPropTypes,
    dispatchPutOKR: PropTypes.func,
    dispatchDeleteKR: PropTypes.func,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  render() {
    const { header, keyResult, dispatchPutOKR, dispatchDeleteKR } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.okr}>サブ目標</div>
          <div className={styles.progress}>進捗</div>
          <div className={styles.owner}>担当者</div>
          <div className={styles.action}>アクション</div>
        </div>);
    }
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate,
      startDate, endDate, owner } = keyResult;
    const { isDeleteKRModalOpen = false, isDeletingKR = false } = this.state || {};
    return (
      <div className={styles.component}>
        <div className={styles.name}>
          <InlineTextArea
            value={name}
            maxLength={120}
            onSubmit={(value, completion) =>
              dispatchPutOKR(id, { okrName: value }).then(completion)}
          />
          <div className={styles.detail}>
            <InlineTextArea
              value={detail}
              maxLength={250}
              onSubmit={(value, completion) =>
                dispatchPutOKR(id, { okrDetail: value }).then(completion)}
            />
          </div>
        </div>
        <div className={styles.progressColumn}>
          <div className={styles.progressBox}>
            <div className={styles.progressPercent}>{achievementRate}%</div>
            <div className={styles.progressBar}>
              <div
                className={this.getProgressStyles(achievementRate)}
                style={{ width: `${achievementRate}%` }}
              />
            </div>
          </div>
          <div className={styles.progressConstituents}>
            {achievedValue}／{targetValue}{unit}
          </div>
          <div className={styles.txt_date}>
            <p>開始日：{startDate}</p>
            <p>期限日：{endDate}</p>
          </div>
        </div>
        <div className={styles.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
        <div className={styles.krCount}>
          <a className={styles.circle} href=""><img src="/img/common/inc_organization.png" alt="Organization" /></a>
          <DropdownMenu
            trigger={<a className={styles.circle} href=""><img src="/img/common/inc_link.png" alt="Link" /></a>}
            options={[
              { caption: '担当者変更' },
              { caption: '紐付け先設定' },
              { caption: '公開範囲設定' },
              { caption: '影響度設定' },
              { caption: '削除', onClick: () => this.setState({ isDeleteKRModalOpen: true }) },
            ]}
          />
        </div>
        {isDeleteKRModalOpen && (
          <DeletionPrompt
            title="サブ目標の削除"
            prompt="こちらのサブ目標を削除しますか?"
            onDelete={() =>
              this.setState({ isDeletingKR: true }, () =>
                dispatchDeleteKR(id).then(({ error }) =>
                  error && this.setState({ isDeletingKR: false })),
              )
            }
            isDeleting={isDeletingKR}
            onClose={() => this.setState({ isDeleteKRModalOpen: false })}
          >
            <div>
              <div>
                <InlineTextInput readonly value={name} />
              </div>
              <div>
                <InlineTextInput readonly value={detail} />
              </div>
              <div className={styles.ownerBox}>
                <div className={styles.ownerImage} />
                <div className={styles.ownerName}>{owner.name}</div>
              </div>
            </div>
          </DeletionPrompt>)}
      </div>);
  }
}
