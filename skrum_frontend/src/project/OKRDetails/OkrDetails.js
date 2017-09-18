import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes } from './propTypes';
import Permissible from '../../components/Permissible';
import InlineTextArea from '../../editors/InlineTextArea';
import InlineDateInput from '../../editors/InlineDateInput';
import ProgressPercentage from '../../components/ProgressPercentage';
import DropdownMenu from '../../components/DropdownMenu';
import Dropdown from '../../components/Dropdown';
import EntityLink from '../../components/EntityLink';
import NewAchievement from '../OKR/NewAchievement/NewAchievement';
import { replacePath, pushToBasic, replaceAsBasic } from '../../util/RouteUtil';
import { withModal } from '../../util/ModalUtil';
import { compareDates } from '../../util/DatetimeUtil';
import { changeOkrOwnerDialog, changeOkrParentDialog, changeOkrDisclosureTypeDialog,
  setRatiosDialog, deleteOkrPrompt } from './dialogs';
import styles from './OkrDetails.css';

/* eslint-disable object-property-newline */

class OkrDetails extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    okr: okrPropTypes.isRequired,
    subject: PropTypes.string.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeOkrOwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { parentOkr, okr, subject, dispatchPutOKR, dispatchChangeOkrOwner,
      dispatchChangeParentOkr, dispatchChangeDisclosureType, dispatchSetRatios,
      dispatchDeleteOkr, openModal } = this.props;
    const { id, name, detail, unit, targetValue, achievedValue, achievementRate,
      startDate, endDate, owner, disclosureType, keyResults = [] } = okr;
    return (
      <div>
        <div className={`${styles.content} ${styles.txt_top} ${styles.cf}`}>
          <p className={`${styles.alignment} ${styles.floatL}`}>紐付け先目標</p>
          <div className={`${styles.txt_content_top} ${styles.floatL} ${styles.clear}`}>
            {parentOkr && (
              <Link to={replacePath({ aspect: 'o', aspectId: parentOkr.id })}>
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
            <ProgressPercentage
              componentClassName={`${styles.bar_top_bottom} ${styles.cf}`}
              {...{ unit, targetValue, achievedValue, achievementRate }}
              fluid
            >
              <span className={styles.floatR}>
                <span className={`${styles.txt_date}`}>開始日：
                  <InlineDateInput
                    value={startDate}
                    required
                    validate={value => compareDates(value, endDate) > 0 && '期限日は開始日以降に設定してください'}
                    onSubmit={value => dispatchPutOKR(id, { startDate: value })}
                  />
                </span>
                <span className={`${styles.txt_date}`}>期限日：
                  <InlineDateInput
                    value={endDate}
                    required
                    validate={value => compareDates(startDate, value) > 0 && '期限日は開始日以降に設定してください'}
                    onSubmit={value => dispatchPutOKR(id, { endDate: value })}
                  />
                </span>
              </span>
            </ProgressPercentage>
            <div className={`${styles.nav_info} ${styles.cf}`}>
              <EntityLink componentClassName={styles.owner_info} entity={owner} />
              <div className={styles.floatR}>
                {keyResults.length === 0 && (
                  <Permissible entity={owner}>
                    <Dropdown
                      triggerIcon="/img/checkin.png"
                      content={props =>
                        <NewAchievement
                          {...{ subject, id, achievedValue, targetValue, unit, ...props }}
                        />}
                      arrow="center"
                    />
                  </Permissible>)}
                <Link
                  className={styles.tool}
                  to={replacePath({ tab: 'map', aspect: 'o', aspectId: id })}
                >
                  <img src="/img/common/inc_organization.png" alt="Map" />
                </Link>
                <Permissible entity={owner}>
                  <DropdownMenu
                    options={[
                      { caption: '担当者変更',
                        onClick: () => openModal(changeOkrOwnerDialog,
                          { id, name, owner, parentOkrOwner: parentOkr.owner,
                            dispatch: dispatchChangeOkrOwner, onSuccess: pushToBasic }) },
                      { caption: '紐付け先設定',
                        onClick: () => openModal(changeOkrParentDialog,
                          { id, parentOkr, okr, dispatch: dispatchChangeParentOkr }) },
                      { caption: '公開範囲設定',
                        onClick: () => openModal(changeOkrDisclosureTypeDialog,
                          { id, name, owner, disclosureType,
                            dispatch: dispatchChangeDisclosureType }) },
                      ...keyResults.length > 0 && [{ caption: '影響度設定',
                        onClick: () => openModal(setRatiosDialog,
                          { id, name, owner, keyResults, dispatch: dispatchSetRatios }) }],
                      { caption: '削除',
                        onClick: () => openModal(deleteOkrPrompt,
                          { id, name, owner, dispatch: dispatchDeleteOkr,
                            onSuccess: replaceAsBasic }) },
                    ]}
                  />
                </Permissible>
              </div>
            </div>
          </div>
        </div>
      </div>);
  }
}

export default withModal(OkrDetails);
