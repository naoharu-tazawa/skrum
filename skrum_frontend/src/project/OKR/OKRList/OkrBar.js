import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { okrPropTypes } from './propTypes';
import Permissible from '../../../components/Permissible';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntityLink, { EntityType } from '../../../components/EntityLink';
import Dropdown from '../../../components/Dropdown';
import DropdownMenu from '../../../components/DropdownMenu';
import NewAchievement from '../../OKR/NewAchievement/NewAchievement';
import NewOneOnOneNote from '../../OneOnOne/NewOneOnOneNote/NewOneOnOneNote';
import { replacePath } from '../../../util/RouteUtil';
import { withModal } from '../../../util/ModalUtil';
import { copyOkrDialog, changeOkrOwnerDialog, changeOkrParentDialog, changeOkrDisclosureTypeDialog,
  setRatiosDialog, deleteOkrPrompt } from '../../OKRDetails/dialogs';
import styles from './OkrBar.css';

class OkrBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    currentUserId: PropTypes.number,
    userId: PropTypes.number,
    userName: PropTypes.string,
    subject: PropTypes.string,
    okr: okrPropTypes,
    onKRClicked: PropTypes.func,
    onAddParentedOkr: PropTypes.func,
    dispatchChangeParentOkr: PropTypes.func,
    dispatchChangeDisclosureType: PropTypes.func,
    dispatchSetRatios: PropTypes.func,
    dispatchChangeOkrOwner: PropTypes.func,
    dispatchCopyOkr: PropTypes.func,
    dispatchDeleteOkr: PropTypes.func,
    openModal: PropTypes.func.isRequired,
    openModeless: PropTypes.func.isRequired,
  };

  render() {
    const { header, currentUserId, userId, userName, subject, okr, onKRClicked, onAddParentedOkr,
      dispatchChangeOkrOwner, dispatchChangeParentOkr, dispatchChangeDisclosureType,
      dispatchSetRatios, dispatchCopyOkr, dispatchDeleteOkr,
      openModal, openModeless } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.okr}>目標</div>
          <div className={styles.progress}>進捗</div>
          <div className={styles.owner}>担当者</div>
          <div className={styles.action}>アクション</div>
        </div>);
    }
    const { id, name, unit, targetValue, achievedValue, achievementRate,
      owner, disclosureType, keyResults, parentOkr } = okr;
    const reportImage = currentUserId === userId ? '/img/memo.png' : '/img/feedback.png';
    return (
      <div className={styles.component}>
        <div className={styles.name}>
          <Link
            to={replacePath({ aspect: 'o', aspectId: id })}
            onMouseUp={e => e.stopPropagation()}
          >
            <span>{name}</span>
          </Link>
        </div>
        <ProgressPercentage
          className={styles.progressColumn}
          {...{ unit, targetValue, achievedValue, achievementRate }}
        />
        <EntityLink className={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          {keyResults.length === 0 && (
            <Permissible entity={owner} fallback={<div className={styles.toolSpace} />}>
              <Dropdown
                triggerIcon="/img/checkin.png"
                content={props =>
                  <NewAchievement
                    basicsOnly
                    {...{ subject, id, achievedValue, targetValue, unit, ...props }}
                  />}
                arrow="right"
              />
            </Permissible>)}
          {keyResults.length !== 0 && <div className={styles.toolSpace} />}
          {userId && (
            <a
              className={styles.tool}
              tabIndex={0}
              onClick={() => openModeless(NewOneOnOneNote, {
                types: currentUserId === userId ? 'progressMemo' : ['hearing', 'feedback', 'interviewNote'],
                ...{ userId, okr },
                feedback: currentUserId === userId ? undefined :
                  { type: EntityType.USER, id: userId, name: userName },
              })}
            >
              <img src={reportImage} alt="1on1" />
            </a>)}
          <Permissible entity={owner}>
            {({ permitted }) => (
              <DropdownMenu
                align="right"
                options={[
                  { caption: 'この目標に紐付ける', onClick: () => onAddParentedOkr(okr) },
                  ...permitted && [{ caption: '担当者変更',
                    onClick: () => openModal(changeOkrOwnerDialog,
                      { ...{ id, name, owner, parentOkrOwner: (parentOkr || {}).owner },
                        dispatch: dispatchChangeOkrOwner }) }],
                  ...permitted && [{ caption: '紐付け先設定',
                    onClick: () => openModal(changeOkrParentDialog,
                      { id, parentOkr, okr, dispatch: dispatchChangeParentOkr }) }],
                  ...permitted && [{ caption: '公開範囲設定',
                    onClick: () => openModal(changeOkrDisclosureTypeDialog,
                      { ...{ id, name, owner, disclosureType },
                        dispatch: dispatchChangeDisclosureType }) }],
                  ...permitted && keyResults.length > 0 && [{ caption: '影響度設定',
                    onClick: () => openModal(setRatiosDialog,
                      { id, name, owner, keyResults, dispatch: dispatchSetRatios }) }],
                  ...permitted && [{ caption: 'コピー',
                    onClick: () => openModal(copyOkrDialog,
                      { id, name, owner, dispatch: dispatchCopyOkr }) }],
                  ...permitted && [{ caption: '削除',
                    onClick: () => openModal(deleteOkrPrompt,
                      { id, name, owner, dispatch: dispatchDeleteOkr }) }],
                ]}
              />)}
          </Permissible>
          {keyResults && (
            <a
              className={`${styles.circle} ${styles.circle_small} ${styles.circle_plus} ${keyResults.length === 0 && styles.invisible}`}
              onClick={onKRClicked}
              tabIndex={0}
            >
              ＋{keyResults.length}
            </a>)}
        </div>
      </div>);
  }
}

export default withModal(OkrBar, { wrapperClassName: styles.wrapper });
