import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { flatten } from 'lodash';
import { okrsPropTypes } from './propTypes';
import OkrBar from './OkrBar';
import KRBar from './KRBar';
import styles from './OKRList.css';

export default class OKRList extends Component {

  static propTypes = {
    currentUserId: PropTypes.number,
    userId: PropTypes.number,
    userName: PropTypes.string,
    subject: PropTypes.string,
    okrs: okrsPropTypes,
    onAddOkr: PropTypes.func,
    onAddParentedOkr: PropTypes.func.isRequired,
    dispatchChangeOkrOwner: PropTypes.func.isRequired,
    dispatchChangeKROwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchCopyOkr: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
  };

  toggleKeyResults(id) {
    const { collapsedKeyResults = {} } = this.state || {};
    const { [id]: expanded = false } = collapsedKeyResults;
    this.setState({ collapsedKeyResults: { ...collapsedKeyResults, [id]: !expanded } });
  }

  render() {
    const { currentUserId, userId, userName, subject, okrs = [], onAddOkr,
      onAddParentedOkr, dispatchChangeOkrOwner, dispatchChangeKROwner, dispatchChangeParentOkr,
      dispatchChangeDisclosureType, dispatchSetRatios, dispatchCopyOkr,
      dispatchDeleteOkr, dispatchDeleteKR } = this.props;
    const { collapsedKeyResults = {} } = this.state || {};
    return (
      <div className={styles.component}>
        <OkrBar header />
        {flatten(okrs.map((okr) => {
          const { id, keyResults } = okr;
          const display = collapsedKeyResults[id] ? 'collapsed' : 'expanded';
          return [
            <OkrBar
              key={`okr${id}`}
              {...{
                currentUserId,
                userId,
                userName,
                subject,
                okr,
                onAddParentedOkr,
                dispatchChangeOkrOwner,
                dispatchChangeParentOkr,
                dispatchChangeDisclosureType,
                dispatchSetRatios,
                dispatchCopyOkr,
                dispatchDeleteOkr,
              }}
              onKRClicked={() => keyResults.length && this.toggleKeyResults(id)}
            />,
            ...keyResults.map(keyResult =>
              <KRBar
                key={`kr${keyResult.id}`}
                {...{
                  display,
                  currentUserId,
                  userId,
                  userName,
                  subject,
                  okr,
                  keyResult,
                  onAddParentedOkr,
                  dispatchChangeKROwner,
                  dispatchChangeDisclosureType,
                  dispatchDeleteKR,
                }}
              />),
          ];
        }))}
        {onAddOkr && (
          <div className={`${styles.footer} ${styles.alignC}`}>
            <button className={styles.addOkr} onClick={onAddOkr}>
              <span className={styles.circle}>
                <img src="/img/common/icn_plus.png" alt="Add" />
              </span>
              <span>新しい目標を追加</span>
            </button>
          </div>)}
      </div>);
  }
}
