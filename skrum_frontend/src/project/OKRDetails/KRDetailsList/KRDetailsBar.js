import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes, keyResultPropTypes } from '../propTypes';
import InlineTextArea from '../../../editors/InlineTextArea';
import InlineDateInput from '../../../editors/InlineDateInput';
import ProgressPercentage from '../../../components/ProgressPercentage';
import DropdownMenu from '../../../components/DropdownMenu';
import Dropdown from '../../../components/Dropdown';
import EntityLink from '../../../components/EntityLink';
import Permissible from '../../../components/Permissible';
import NewAchievement from '../../OKR/NewAchievement/NewAchievement';
import { replacePath } from '../../../util/RouteUtil';
import { withModal } from '../../../util/ModalUtil';
import { compareDates } from '../../../util/DatetimeUtil';
import { OKRType } from '../../../util/OKRUtil';
import { changeKRDisclosureTypeDialog, deleteKRPrompt } from '../dialogs';
import styles from './KRDetailsBar.css';

class KRDetailsBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    parentOkr: okrPropTypes,
    keyResult: keyResultPropTypes,
    subject: PropTypes.string,
    dispatchPutOKR: PropTypes.func,
    dispatchChangeKROwner: PropTypes.func,
    dispatchChangeParentOkr: PropTypes.func,
    dispatchChangeDisclosureType: PropTypes.func,
    dispatchDeleteKR: PropTypes.func,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { header, /* parentOkr, */ keyResult, subject, dispatchPutOKR,
      dispatchChangeDisclosureType, dispatchDeleteKR, openModal } = this.props;
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
          <Permissible entity={owner}>
            {({ permitted }) => (
              <InlineTextArea
                value={name}
                required
                maxLength={120}
                readonly={!permitted}
                onSubmit={value => dispatchPutOKR(id, { okrName: value })}
              />)}
          </Permissible>
          <div className={styles.detail}>
            <Permissible entity={owner}>
              {({ permitted }) => (
                <InlineTextArea
                  value={detail}
                  placeholder="目標詳細を入力してください"
                  maxLength={250}
                  readonly={!permitted}
                  onSubmit={value => dispatchPutOKR(id, { okrDetail: value })}
                />)}
            </Permissible>
          </div>
        </div>
        <ProgressPercentage
          componentClassName={styles.progressColumn}
          {...{ unit, targetValue, achievedValue, achievementRate }}
        >
          <div className={styles.txt_date}>
            <div>開始日：
              <Permissible entity={owner}>
                {({ permitted }) => (
                  <InlineDateInput
                    value={startDate}
                    required
                    validate={value => compareDates(value, endDate) > 0 && '期限日は開始日以降に設定してください'}
                    readonly={!permitted}
                    onSubmit={value => dispatchPutOKR(id, { startDate: value })}
                  />)}
              </Permissible>
            </div>
            <div>期限日：
              <Permissible entity={owner}>
                {({ permitted }) => (
                  <InlineDateInput
                    value={endDate}
                    required
                    validate={value => compareDates(startDate, value) > 0 && '期限日は開始日以降に設定してください'}
                    readonly={!permitted}
                    onSubmit={value => dispatchPutOKR(id, { endDate: value })}
                  />)}
              </Permissible>
            </div>
          </div>
        </ProgressPercentage>
        <EntityLink componentClassName={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          {type === OKRType.KR && (
            <Permissible entity={owner} fallback={<div className={styles.checkinSpace} />}>
              <Dropdown
                triggerIcon="/img/checkin.png"
                content={props =>
                  <NewAchievement
                    {...{ subject, id, achievedValue, targetValue, unit, ...props }}
                  />}
                arrow="right"
              />
            </Permissible>)}
          {type === OKRType.OKR && <div className={styles.checkinSpace} />}
          <Link
            className={styles.tool}
            to={replacePath({ tab: 'map', aspect: 'o', aspectId: id })}
          >
            <img src="/img/common/inc_organization.png" alt="Map" />
          </Link>
          <Permissible entity={owner}>
            <DropdownMenu
              options={[
                // { caption: '担当者変更',
                //   onClick: () => openModal(changeKROwnerDialog,
                //     { id, name, owner, dispatch: dispatchChangeKROwner }) },
                // { caption: '紐付け先設定',
                //   onClick: () => openModal(this.changeParentOkrDialog,
                //     { id, parentOkr, keyResult, dispatch: dispatchChangeParentOkr }) },
                { caption: '公開範囲設定',
                  onClick: () => openModal(changeKRDisclosureTypeDialog,
                    { id, name, owner, disclosureType, dispatch: dispatchChangeDisclosureType }) },
                { caption: '削除',
                  onClick: () => openModal(deleteKRPrompt,
                    { id, name, owner, dispatch: dispatchDeleteKR }) },
              ]}
            />
          </Permissible>
        </div>
      </div>);
  }
}

export default withModal(KRDetailsBar);
