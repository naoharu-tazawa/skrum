import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { join } from 'lodash';
import { okrPropTypes, keyResultPropTypes } from './propTypes';
import Permissible from '../../../components/Permissible';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntityLink from '../../../components/EntityLink';
import Dropdown from '../../../components/Dropdown';
import DropdownMenu from '../../../components/DropdownMenu';
import NewAchievement from '../../OKR/NewAchievement/NewAchievement';
import { OKRType } from '../../../util/OKRUtil';
import { withModal } from '../../../util/ModalUtil';
import { changeKROwnerDialog, changeKRDisclosureTypeDialog, deleteKRPrompt } from '../../OKRDetails/dialogs';
import styles from './KRBar.css';

class KRBar extends Component {

  static propTypes = {
    display: PropTypes.oneOf(['expanded', 'collapsed']).isRequired,
    subject: PropTypes.string.isRequired,
    okr: okrPropTypes.isRequired,
    keyResult: keyResultPropTypes.isRequired,
    onAddParentedOkr: PropTypes.func.isRequired,
    dispatchChangeKROwner: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  getBaseStyles = (display) => {
    const baseStyles = [
      styles.component,
      ...[display === 'collapsed' ? [styles.collapsed] : []],
    ];
    return join(baseStyles, ' ');
  };

  render() {
    const { display, subject, okr, keyResult, onAddParentedOkr, dispatchChangeKROwner,
      dispatchChangeDisclosureType, dispatchDeleteKR, openModal } = this.props;
    const parentOkrOwner = okr.owner;
    const { id, type, name, unit, targetValue, achievedValue, achievementRate, owner,
      disclosureType } = keyResult;
    return (
      <div className={this.getBaseStyles(display)}>
        <div className={styles.name}>
          {name}
        </div>
        <ProgressPercentage
          className={styles.progressColumn}
          {...{ unit, targetValue, achievedValue, achievementRate }}
        />
        <EntityLink className={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          {type === OKRType.KR && (
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
          {type !== OKRType.KR && <div className={styles.toolSpace} />}
          <Permissible entity={owner}>
            {({ permitted }) => (
              <DropdownMenu
                options={[
                  { caption: 'この目標に紐付ける', onClick: () => onAddParentedOkr(keyResult) },
                  ...permitted && [{ caption: '担当者変更',
                    onClick: () => openModal(changeKROwnerDialog,
                      { id, name, owner, parentOkrOwner, dispatch: dispatchChangeKROwner }) }],
                  ...permitted && [{ caption: '公開範囲設定',
                    onClick: () => openModal(changeKRDisclosureTypeDialog,
                      { ...{ id, name, owner, disclosureType },
                        dispatch: dispatchChangeDisclosureType }) }],
                  ...permitted && [{ caption: '削除',
                    onClick: () => openModal(deleteKRPrompt,
                      { id, name, owner, dispatch: dispatchDeleteKR }) }],
                ]}
              />)}
          </Permissible>
        </div>
      </div>);
  }
}

export default withModal(KRBar);
