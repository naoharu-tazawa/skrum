import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { isEmpty } from 'lodash';
import { okrPropTypes, keyResultPropTypes } from '../propTypes';
import InlineTextArea from '../../../editors/InlineTextArea';
import InlineDateInput from '../../../editors/InlineDateInput';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import DialogForm from '../../../dialogs/DialogForm';
import EntitySubject from '../../../components/EntitySubject';
import DropdownMenu from '../../../components/DropdownMenu';
import Dropdown from '../../../components/Dropdown';
import DisclosureTypeOptions from '../../../components/DisclosureTypeOptions';
import EntityLink from '../../../components/EntityLink';
import OwnerSearch from '../../OwnerSearch/OwnerSearch';
// import OKRSearch from '../../OKRSearch/OKRSearch';
import NewAchievement from '../../OKR/NewAchievement/NewAchievement';
import { withModal } from '../../../util/ModalUtil';
import { compareDates } from '../../../util/DatetimeUtil';
import { OKRType } from '../../../util/OKRUtil';
import styles from './KRDetailsBar.css';

class KRDetailsBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    parentOkr: okrPropTypes,
    keyResult: keyResultPropTypes,
    dispatchPutOKR: PropTypes.func,
    dispatchChangeOwner: PropTypes.func,
    dispatchChangeParentOkr: PropTypes.func,
    dispatchChangeDisclosureType: PropTypes.func,
    dispatchDeleteKR: PropTypes.func,
    openModal: PropTypes.func.isRequired,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  changeOwnerDialog = ({ id, name, owner, onClose }) => (
    <DialogForm
      title="担当者の変更"
      submitButton="変更"
      onSubmit={({ changedOwner } = {}) => this.props.dispatchChangeOwner(id, changedOwner)}
      valid={({ changedOwner }) => !isEmpty(changedOwner) &&
        (changedOwner.type !== owner.type || changedOwner.id !== owner.id)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={owner} heading="担当者を変更するサブ目標" subject={name} />
          <section>
            <label>担当者検索</label>
            <OwnerSearch onChange={value => setFieldData({ changedOwner: value })} />
          </section>
        </div>}
    </DialogForm>);

  /* changeParentOkrDialog = ({ id, parentOkr = {}, keyResult, onClose }) => (
    <DialogForm
      title="サブ目標の紐付け先変更"
      submitButton="変更"
      onSubmit={({ changedParent } = {}) =>
        this.props.dispatchChangeParentOkr(id, changedParent.id)}
      valid={({ changedParent }) => !isEmpty(changedParent) && changedParent.id !== parentOkr.id}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject
            entity={keyResult.owner}
            heading="紐付け先目標を変更するサブ目標（子目標）"
            subject={keyResult.name}
          />
          <div className={styles.parentOkrToChange}>
            <EntitySubject
              entity={parentOkr.owner}
              heading="現在の紐付け先目標（親目標）"
              subject={parentOkr.name}
            />
          </div>
          <section>
            <label>紐付け先検索</label>
            <OKRSearch
              owner={keyResult.owner}
              onChange={value => setFieldData({ changedParent: value })}
            />
          </section>
        </div>}
    </DialogForm>); */

  changeDisclosureTypeDialog = ({ id, name, owner, disclosureType, onClose }) => (
    <DialogForm
      title="公開範囲設定変更"
      submitButton="設定"
      onSubmit={({ changedDisclosureType } = {}) =>
        (!changedDisclosureType || changedDisclosureType === disclosureType ? Promise.resolve() :
          this.props.dispatchChangeDisclosureType(id, changedDisclosureType))}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={owner} heading="対象サブ目標" subject={name} />
          <section>
            <label>公開範囲</label>
            <DisclosureTypeOptions
              entityType={owner.type}
              renderer={({ value, label }) => (
                <label key={value}>
                  <input
                    name="disclosureType"
                    type="radio"
                    defaultChecked={value === disclosureType}
                    onClick={() => setFieldData({ changedDisclosureType: value })}
                  />
                  {label}
                </label>)}
            />
          </section>
        </div>}
    </DialogForm>);

  deleteKRPrompt = ({ id, name, owner, onClose }) => (
    <ConfirmationPrompt
      title="サブ目標の削除"
      prompt="こちらのサブ目標を削除しますか？"
      warning={(
        <ul>
          <li>一度削除したサブ目標は復元できません。</li>
          <li>上記のサブ目標に直接紐づいている全ての目標/サブ目標、およびその下に紐づいている全ての目標/サブ目標も同時に削除されます。</li>
        </ul>
      )}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchDeleteKR(id)}
      onClose={onClose}
    >
      <EntitySubject entity={owner} subject={name} />
    </ConfirmationPrompt>);

  render() {
    const { header, /* parentOkr, */ keyResult, dispatchPutOKR, openModal } = this.props;
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
      startDate, endDate, owner, disclosureType } = keyResult;
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
                validate={value => compareDates(value, endDate) > 0 && '期限日は開始日以降に設定してください'}
                onSubmit={value => dispatchPutOKR(id, { startDate: value })}
              />
            </div>
            <div>期限日：
              <InlineDateInput
                value={endDate}
                required
                validate={value => compareDates(startDate, value) > 0 && '期限日は開始日以降に設定してください'}
                onSubmit={value => dispatchPutOKR(id, { endDate: value })}
              />
            </div>
          </div>
        </div>
        <EntityLink componentClassName={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          {type === OKRType.KR && (
            <Dropdown
              trigger={<button className={styles.tool}><img src="/img/checkin.png" alt="Achievement" /></button>}
              content={props =>
                <NewAchievement {...{ id, achievedValue, targetValue, unit, ...props }} />}
              arrow="right"
            />)}
          {type === OKRType.OKR && <div className={styles.checkinSpace} />}
          <a className={styles.tool} href=""><img src="/img/common/inc_organization.png" alt="Map" /></a>
          <DropdownMenu
            trigger={<button className={styles.tool}><img src="/img/common/inc_link.png" alt="Menu" /></button>}
            options={[
              { caption: '担当者変更',
                onClick: () => openModal(this.changeOwnerDialog,
                  { id, name, owner }) },
              // { caption: '紐付け先設定',
              //   onClick: () => openModal(this.changeParentOkrDialog,
              //     { id, parentOkr, keyResult }) },
              { caption: '公開範囲設定',
                onClick: () => openModal(this.changeDisclosureTypeDialog,
                  { id, name, owner, disclosureType }) },
              { caption: '影響度設定' },
              { caption: '削除',
                onClick: () => openModal(this.deleteKRPrompt, { id, name, owner }) },
            ]}
          />
        </div>
      </div>);
  }
}

export default withModal(KRDetailsBar);
