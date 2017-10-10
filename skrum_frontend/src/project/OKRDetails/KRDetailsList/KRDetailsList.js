import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { okrPropTypes, keyResultsPropTypes } from '../propTypes';
import KRDetailsBar from './KRDetailsBar';
import Permissible from '../../../components/Permissible';
import { withModal } from '../../../util/ModalUtil';
import { setRatiosDialog } from '../dialogs';
import styles from './KRDetailsList.css';

class KRDetailsList extends Component {

  static propTypes = {
    parentOkr: okrPropTypes,
    keyResults: keyResultsPropTypes,
    subject: PropTypes.string.isRequired,
    onAdd: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeKROwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { parentOkr, keyResults = [], subject, onAdd, dispatchSetRatios,
      dispatchPutOKR, dispatchChangeKROwner, dispatchChangeParentOkr, dispatchChangeDisclosureType,
      dispatchDeleteKR, openModal } = this.props;
    const { id, name, owner } = parentOkr;
    return (
      <div className={styles.component}>
        <KRDetailsBar header />
        <Permissible entity={parentOkr.owner}>
          {({ permitted }) => (
            <div className={styles.bars}>
              {keyResults.map((keyResult, index) =>
                <KRDetailsBar
                  key={`kr-details-${keyResult.id}`}
                  {...{
                    parentOkr,
                    keyResult,
                    subject,
                    onRatioClick: !permitted ? null : (() => openModal(setRatiosDialog,
                      { ...{ id, name, owner, keyResults, selected: index },
                        dispatch: dispatchSetRatios })),
                    dispatchPutOKR,
                    dispatchChangeKROwner,
                    dispatchChangeParentOkr,
                    dispatchChangeDisclosureType,
                    dispatchDeleteKR,
                  }}
                />)}
            </div>)}
        </Permissible>
        <Permissible entity={parentOkr.owner}>
          <div className={`${styles.footer} ${styles.alignC}`}>
            <button className={styles.addKR} onClick={onAdd}>
              <span className={styles.circle}>
                <img src="/img/common/icn_plus.png" alt="Add" />
              </span>
              <span>新しいサブ目標を追加</span>
            </button>
          </div>
        </Permissible>
      </div>);
  }
}

export default withModal(KRDetailsList);
