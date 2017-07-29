import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link, browserHistory } from 'react-router';
import { isEmpty } from 'lodash';
import { okrPropTypes } from './propTypes';
import { replacePath, toBasicPath } from '../../util/RouteUtil';
import InlineTextArea from '../../editors/InlineTextArea';
import InlineDateInput from '../../editors/InlineDateInput';
import DeletionPrompt from '../../dialogs/DeletionPrompt';
import DialogForm from '../../dialogs/DialogForm';
import EntitySubject from '../../components/EntitySubject';
import DropdownMenu from '../../components/DropdownMenu';
import Dropdown from '../../components/Dropdown';
import DisclosureTypeOptions from '../../components/DisclosureTypeOptions';
import EntityLink from '../../components/EntityLink';
import OwnerSearch from '../OwnerSearch/OwnerSearch';
import OKRSearch from '../OKRSearch/OKRSearch';
import NewAchievement from '../OKR/NewAchievement/NewAchievement';
import { withModal } from '../../util/ModalUtil';
import { compareDates } from '../../util/DatetimeUtil';
import styles from './OkrDetails.css';

class OkrDetails extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    okr: okrPropTypes.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeOwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
    closeActiveModal: PropTypes.func.isRequired,
  };

  getProgressStyles = rate =>
    `${styles.progress} ${rate >= 70 ? styles.high : `${rate >= 30 ? styles.mid : styles.low}`}`;

  changeOwnerDialog = ({ id, name, owner, onClose }) => (
    <DialogForm
      title="担当者の変更"
      submitButton="変更"
      onSubmit={({ changedOwner } = {}) =>
        this.props.dispatchChangeOwner(id, changedOwner).then(({ error }) =>
          !error && this.props.closeActiveModal(),
        )}
      valid={({ changedOwner }) => !isEmpty(changedOwner) &&
        (changedOwner.type !== owner.type || changedOwner.id !== owner.id)}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={owner} heading="担当者を変更する目標" subject={name} />
          <section>
            <label>担当者検索</label>
            <OwnerSearch onChange={value => setFieldData({ changedOwner: value })} />
          </section>
        </div>}
    </DialogForm>);

  changeParentOkrDialog = ({ id, parentOkr = {}, okr, onClose }) => (
    <DialogForm
      title="目標の紐付け先設定/変更"
      submitButton="変更"
      onSubmit={({ changedParent } = {}) =>
        this.props.dispatchChangeParentOkr(id, changedParent.id).then(({ error }) =>
          !error && this.props.closeActiveModal(),
        )}
      valid={({ changedParent }) => !isEmpty(changedParent) && changedParent.id !== parentOkr.id}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject
            entity={okr.owner}
            heading="紐付け先目標を設定または変更する目標（子目標）"
            subject={okr.name}
          />
          <div className={styles.parentOkrToChange}>
            <EntitySubject
              entity={parentOkr.owner}
              heading="現在の紐付け先目標/サブ目標（親目標）"
              subject={parentOkr.name}
            />
          </div>
          <section>
            <label>紐付け先検索</label>
            <OKRSearch
              owner={okr.owner}
              onChange={value => setFieldData({ changedParent: value })}
            />
          </section>
        </div>}
    </DialogForm>);

  changeDisclosureTypeDialog = ({ id, name, owner, disclosureType, onClose }) => (
    <DialogForm
      title="公開範囲設定変更"
      submitButton="設定"
      onSubmit={({ changedDisclosureType } = {}) =>
        (!changedDisclosureType || changedDisclosureType === disclosureType ?
          Promise.resolve(this.props.closeActiveModal()) :
          this.props.dispatchChangeDisclosureType(id, changedDisclosureType).then(({ error }) =>
            !error && this.props.closeActiveModal(),
          ))}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <EntitySubject entity={owner} heading="対象目標" subject={name} />
          <section>
            <label>公開範囲</label>
            <DisclosureTypeOptions
              ownerType={owner.type}
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

  deleteOkrPrompt = ({ id, name, owner }) => (
    <DeletionPrompt
      title="目標の削除"
      prompt="こちらの目標を削除しますか？"
      warning={(
        <ul>
          <li>一度削除した目標は復元できません。</li>
          <li>上記の目標に直接紐づいている全ての目標/サブ目標、およびその下に紐づいている全ての目標/サブ目標も同時に削除されます。</li>
        </ul>
      )}
      onDelete={() => this.props.dispatchDeleteOkr(id).then(({ error }) => {
        if (!error) this.props.closeActiveModal();
        if (!error) browserHistory.push(toBasicPath());
      })}
      onClose={() => this.props.closeActiveModal()}
    >
      <EntitySubject entity={owner} subject={name} />
    </DeletionPrompt>);

  render() {
    const { parentOkr, okr, dispatchPutOKR, openModal } = this.props;
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate,
      startDate, endDate, owner, disclosureType, keyResults = [] } = okr;
    return (
      <div>
        <div className={`${styles.content} ${styles.txt_top} ${styles.cf}`}>
          <p className={`${styles.alignment} ${styles.floatL}`}>紐付け先目標</p>
          <div className={`${styles.txt_content_top} ${styles.floatL} ${styles.clear}`}>
            {parentOkr && (
              <Link to={replacePath({ aspect: 'o', aspectId: `${parentOkr.id}` })}>
                {parentOkr.name}
              </Link>)}
            {!parentOkr && <span>➖</span>}
          </div>
          {parentOkr && (
            <EntityLink
              componentClassName={`${styles.parent_owner_info} ${styles.floatR}`}
              entity={parentOkr.owner}
            />)}
        </div>
        <div className={`${styles.content} ${styles.cf}`}>
          <div className={styles.boxInfo}>
            <div className={styles.ttl_team}>
              <InlineTextArea
                value={name}
                required
                maxLength={120}
                onSubmit={value => dispatchPutOKR(id, { okrName: value })}
              />
            </div>
            <div className={styles.txt}>
              <InlineTextArea
                value={detail}
                placeholder="目標詳細を入力してください"
                maxLength={250}
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
                <span>開始日：
                  <InlineDateInput
                    value={startDate}
                    required
                    validate={value => compareDates(value, endDate) > 0 && '終了日は開始日以降に設定してください'}
                    onSubmit={value => dispatchPutOKR(id, { startDate: value })}
                  />
                </span>
                <span>期限日：
                  <InlineDateInput
                    value={endDate}
                    required
                    validate={value => compareDates(startDate, value) > 0 && '終了日は開始日以降に設定してください'}
                    onSubmit={value => dispatchPutOKR(id, { endDate: value })}
                  />
                </span>
              </div>
            </div>
            <div className={`${styles.nav_info} ${styles.cf}`}>
              <EntityLink componentClassName={styles.owner_info} entity={owner} />
              <div className={styles.floatR}>
                {keyResults.length === 0 && (
                  <Dropdown
                    trigger={(
                      <button className={styles.tool}><img src="/img/checkin.png" alt="Achievement" /></button>)}
                    content={props =>
                      <NewAchievement {...{ id, achievedValue, targetValue, unit, ...props }} />}
                    arrow="center"
                  />)}
                <a className={styles.tool} href=""><img src="/img/common/inc_organization.png" alt="Map" /></a>
                <DropdownMenu
                  trigger={(
                    <button className={styles.tool}><img src="/img/common/inc_link.png" alt="Menu" /></button>)}
                  options={[
                    { caption: '担当者変更',
                      onClick: () => openModal(this.changeOwnerDialog,
                        { id, name, owner }) },
                    { caption: '紐付け先設定',
                      onClick: () => openModal(this.changeParentOkrDialog,
                        { id, parentOkr, okr }) },
                    { caption: '公開範囲設定',
                      onClick: () => openModal(this.changeDisclosureTypeDialog,
                        { id, name, owner, disclosureType }) },
                    { caption: '削除',
                      onClick: () => openModal(this.deleteOkrPrompt({ id, name, owner })) },
                  ]}
                />
              </div>
            </div>
          </div>
        </div>
      </div>);
  }
}

export default withModal(OkrDetails);
