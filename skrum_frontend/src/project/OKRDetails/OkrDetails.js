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
import OwnerSubject from '../../components/OwnerSubject';
import DropdownMenu from '../../components/DropdownMenu';
import Dropdown from '../../components/Dropdown';
import DisclosureTypeOptions from '../../components/DisclosureTypeOptions';
import OwnerSearch from '../OwnerSearch/OwnerSearch';
import NewAchievement from '../OKR/NewAchievement/NewAchievement';
import { withBasicModalDialog } from '../../util/FormUtil';
import { compareDates } from '../../util/DatetimeUtil';
import styles from './OkrDetails.css';

export default class OkrDetails extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    okr: okrPropTypes.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeOwner: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
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

  changeDisclosureType = ({ id, name, owner, disclosureType, onClose }) => (
    <DialogForm
      title="公開範囲設定変更"
      submitButton="設定"
      onSubmit={({ changedDisclosureType } = {}) =>
        (!changedDisclosureType || changedDisclosureType === disclosureType ?
          Promise.resolve(this.setState({ changeDisclosureType: null })) :
          this.props.dispatchChangeDisclosureType(id, changedDisclosureType).then(({ error }) =>
            !error && this.setState({ changeDisclosureType: null }),
          ))}
      onClose={onClose}
    >
      {({ setFieldData }) =>
        <div>
          <OwnerSubject owner={owner} heading="対象目標" subject={name} />
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
        if (!error) this.setState({ deleteOkrPrompt: null });
        if (!error) browserHistory.push(toBasicPath());
      })}
      onClose={() => this.setState({ deleteOkrPrompt: null })}
    >
      <OwnerSubject owner={owner} subject={name} />
    </DeletionPrompt>);

  render() {
    const { parentOkr = {}, okr, dispatchPutOKR } = this.props;
    const { changeOwnerPrompt, changeDisclosureType, deleteOkrPrompt } = this.state || {};
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
            <div className={`${styles.img_content_top} ${styles.floatR}`}>
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
              <div className={`${styles.owner_info} ${styles.user_info} ${styles.floatL} ${styles.cf}`}>
                <div className={`${styles.avatar} ${styles.floatL}`}>
                  <img src="/img/common/icn_user.png" alt="User Name" />
                </div>
                <div className={`${styles.info} ${styles.floatL}`}>
                  <div className={styles.user_name}>{owner.name}</div>
                </div>
              </div>
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
                      onClick: () => this.setState({ changeOwnerPrompt:
                        withBasicModalDialog(
                          this.changeOwnerPrompt,
                          () => this.setState({ changeOwnerPrompt: null }),
                          { id, name, owner }) }) },
                    { caption: '紐付け先設定' },
                    { caption: '公開範囲設定',
                      onClick: () => this.setState({ changeDisclosureType:
                        withBasicModalDialog(
                          this.changeDisclosureType,
                          () => this.setState({ changeDisclosureType: null }),
                          { id, name, owner, disclosureType }) }) },
                    { caption: '削除',
                      onClick: () => this.setState({ deleteOkrPrompt:
                        this.deleteOkrPrompt({ id, name, owner }) }) },
                  ]}
                />
              </div>
            </div>
          </div>
        </div>
        {changeOwnerPrompt}
        {changeDisclosureType}
        {deleteOkrPrompt}
      </div>);
  }
}
