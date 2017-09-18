import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { join } from 'lodash';
import Permissible from '../../../components/Permissible';
import ProgressPercentage from '../../../components/ProgressPercentage';
import EntityLink from '../../../components/EntityLink';
import DropdownMenu from '../../../components/DropdownMenu';
import { replacePath } from '../../../util/RouteUtil';
import { withModal } from '../../../util/ModalUtil';
import { keyResultPropTypes } from './propTypes';
import { changeKRDisclosureTypeDialog, deleteKRPrompt } from '../../OKRDetails/dialogs';
import styles from './KRBar.css';

class KRBar extends Component {

  static propTypes = {
    display: PropTypes.oneOf(['expanded', 'collapsed']).isRequired,
    keyResult: keyResultPropTypes.isRequired,
    onAddParentedOkr: PropTypes.func.isRequired,
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
    const { display, keyResult, onAddParentedOkr,
      dispatchChangeDisclosureType, dispatchDeleteKR, openModal } = this.props;
    const { id, name, unit, targetValue, achievedValue, achievementRate, owner,
      disclosureType } = keyResult;
    return (
      <div className={this.getBaseStyles(display)}>
        <div className={styles.name}>
          {name}
        </div>
        <ProgressPercentage
          componentClassName={styles.progressColumn}
          {...{ unit, targetValue, achievedValue, achievementRate }}
        />
        <EntityLink componentClassName={styles.ownerBox} entity={owner} />
        <div className={styles.action}>
          <Link
            className={styles.circle}
            to={replacePath({ tab: 'map', aspect: 'o', aspectId: id })}
          >
            <img src="/img/common/inc_organization.png" alt="Map" />
          </Link>
          <Permissible entity={owner}>
            {({ permitted }) => (
              <DropdownMenu
                options={[
                  { caption: 'この目標に紐付ける', onClick: () => onAddParentedOkr(keyResult) },
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
