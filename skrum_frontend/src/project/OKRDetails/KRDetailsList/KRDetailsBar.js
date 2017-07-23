import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { isEmpty } from 'lodash';
import { keyResultPropTypes } from '../propTypes';
import InlineTextArea from '../../../editors/InlineTextArea';
import InlineDateInput from '../../../editors/InlineDateInput';
import DeletionPrompt from '../../../dialogs/DeletionPrompt';
import DialogForm from '../../../dialogs/DialogForm';
import OwnerSubject from '../../../components/OwnerSubject';
import DropdownMenu from '../../../components/DropdownMenu';
import Dropdown from '../../../components/Dropdown';
import OwnerSearch from '../../OwnerSearch/OwnerSearch';
import NewAchievement from '../../OKR/NewAchievement/NewAchievement';
import { withBasicModalDialog } from '../../../util/FormUtil';
import { compareDates } from '../../../util/DatetimeUtil';
import styles from './KRDetailsBar.css';

export default class KRDetailsBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    keyResult: keyResultPropTypes,
    dispatchPutOKR: PropTypes.func,
    dispatchChangeOwner: PropTypes.func,
    dispatchDeleteKR: PropTypes.func,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  changeOwnerPrompt = ({ id, name, owner, onClose }) => (
    <DialogForm
      title="担当者の変更"
      submitButton="変更"
      onSubmit={({ changedOwner } = {}) =>
        this.props.dispatchChangeOwner(id, changedOwner).then(({ error }) =>
          !error && this.setState({ changeOwnerPrompt: null }),
        )}
      valid={({ changedOwner }) => !isEmpty(changedOwner) &&
        (changedOwner.type !== owner.type || changedOwner.id !== owner.id)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <OwnerSubject owner={owner} heading="担当者を変更する目標" subject={name} />
          <section>
            <label>担当者検索</label>
            <OwnerSearch onChange={value => setFieldData({ changedOwner: value })} />
          </section>
        </div>}
    </DialogForm>);

  deleteKRPrompt = ({ id, name, owner }) => (
    <DeletionPrompt
      title="サブ目標の削除"
      prompt="こちらのサブ目標を削除しますか？"
      warning={(
        <ul>
          <li>一度削除したサブ目標は復元できません。</li>
          <li>上記のサブ目標に直接紐づいている全ての目標/サブ目標、およびその下に紐づいている全ての目標/サブ目標も同時に削除されます。</li>
        </ul>
      )}
      onDelete={() => this.props.dispatchDeleteKR(id)}
      onClose={() => this.setState({ deleteKRPrompt: null })}
    >
      <OwnerSubject owner={owner} subject={name} />
    </DeletionPrompt>);

  render() {
    const { header, keyResult, dispatchPutOKR } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.okr}>サブ目標</div>
          <div className={styles.progress}>進捗</div>
          <div className={styles.owner}>担当者</div>
          <div className={styles.action}>アクション</div>
        </div>);
    }
    const { id, type, name, detail, unit, targetValue, achievedValue, achievementRate,
      startDate, endDate, owner } = keyResult;
    const { changeOwnerPrompt, deleteKRPrompt } = this.state || {};
    return (
      <div className={styles.component}>
        <div className={styles.name}>
          <InlineTextArea
            value={name}
            required
            maxLength={120}
            onSubmit={value => dispatchPutOKR(id, { okrName: value })}
          />
          <div className={styles.detail}>
            <InlineTextArea
              value={detail}
              placeholder="目標詳細を入力してください"
              maxLength={250}
              onSubmit={value => dispatchPutOKR(id, { okrDetail: value })}
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
            <div>開始日：
              <InlineDateInput
                value={startDate}
                required
                validate={value => compareDates(value, endDate) > 0 && '終了日は開始日以降に設定してください'}
                onSubmit={value => dispatchPutOKR(id, { startDate: value })}
              />
            </div>
            <div>期限日：
              <InlineDateInput
                value={endDate}
                required
                validate={value => compareDates(startDate, value) > 0 && '終了日は開始日以降に設定してください'}
                onSubmit={value => dispatchPutOKR(id, { endDate: value })}
              />
            </div>
          </div>
        </div>
        <div className={styles.ownerBox}>
          <div className={styles.ownerImage} />
          <div className={styles.ownerName}>{owner.name}</div>
        </div>
        <div className={styles.krCount}>
          {type === '2' && (
            <Dropdown
              trigger={<button className={styles.tool}><img src="/img/checkin.png" alt="Achievement" /></button>}
              content={props =>
                <NewAchievement {...{ id, achievedValue, targetValue, unit, ...props }} />}
              arrow="right"
            />)}
          <a className={styles.tool} href=""><img src="/img/common/inc_organization.png" alt="Map" /></a>
          <DropdownMenu
            trigger={<button className={styles.tool}><img src="/img/common/inc_link.png" alt="Menu" /></button>}
            options={[
              { caption: '担当者変更',
                onClick: () => this.setState({ changeOwnerPrompt:
                  withBasicModalDialog(
                    this.changeOwnerPrompt,
                    () => this.setState({ changeOwnerPrompt: null }),
                    { id, name, owner }) }) },
              { caption: '紐付け先設定' },
              { caption: '公開範囲設定' },
              { caption: '影響度設定' },
              { caption: '削除',
                onClick: () => this.setState({ deleteKRPrompt:
                  this.deleteKRPrompt({ id, name, owner }) }) },
            ]}
          />
        </div>
        {changeOwnerPrompt}
        {deleteKRPrompt}
      </div>);
  }
}
