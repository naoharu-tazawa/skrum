import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { flatten } from 'lodash';
import { okrsPropTypes } from './propTypes';
import OkrBar from './OkrBar';
import KRBar from './KRBar';
import styles from './OKRList.css';

export default class OKRList extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
    onAddOkr: PropTypes.func.isRequired,
    onAddParentedOkr: PropTypes.func.isRequired,
    dispatchChangeOkrOwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    dispatchDeleteKR: PropTypes.func.isRequired,
  };

  toggleKeyResults(id) {
    const { expandedKeyResults = {} } = this.state || {};
    const { [id]: expanded = false } = expandedKeyResults;
    this.setState({ expandedKeyResults: { ...expandedKeyResults, [id]: !expanded } });
  }

  render() {
    const { okrs = [], onAddOkr, onAddParentedOkr, dispatchChangeOkrOwner,
      dispatchChangeParentOkr, dispatchChangeDisclosureType, dispatchSetRatios,
      dispatchDeleteOkr, dispatchDeleteKR } = this.props;
    const { expandedKeyResults = {} } = this.state || {};
    return (
      <div className={styles.component}>
        <div className={styles.header}>
          <OkrBar header />
        </div>
        <div className={styles.bars}>
          {flatten(okrs.map((okr) => {
            const { id, keyResults } = okr;
            const display = expandedKeyResults[id] ? 'expanded' : 'collapsed';
            return [
              (<div key={`okr-${id}`}>
                <OkrBar
                  {...{
                    okr,
                    onAddParentedOkr,
                    dispatchChangeOkrOwner,
                    dispatchChangeParentOkr,
                    dispatchChangeDisclosureType,
                    dispatchSetRatios,
                    dispatchDeleteOkr,
                  }}
                  onKRClicked={() => keyResults.length && this.toggleKeyResults(id)}
                />
              </div>),
              ...keyResults.map(keyResult =>
                <KRBar
                  key={`kr-${keyResult.id}`}
                  {...{
                    display,
                    keyResult,
                    onAddParentedOkr,
                    dispatchChangeDisclosureType,
                    dispatchDeleteKR,
                  }}
                />),
            ];
          }))}
        </div>
        <div className={`${styles.footer} ${styles.alignC}`}>
          <button className={styles.addOkr} onClick={onAddOkr}>
            <span className={styles.circle}>
              <img src="/img/common/icn_plus.png" alt="Add" />
            </span>
            <span>新しい目標を追加</span>
          </button>
        </div>
      </div>);
  }
}
