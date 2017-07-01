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
    onAdd: PropTypes.func.isRequired,
  };

  toggleKeyResults(id) {
    const { expandedKeyResults } = this.state;
    const { [id]: expanded = false } = expandedKeyResults;
    this.setState({ expandedKeyResults: { ...expandedKeyResults, [id]: !expanded } });
  }

  render() {
    const { okrs = [], onAdd } = this.props;
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
              (<div key={id}>
                <OkrBar
                  okr={okr}
                  onKRClicked={() => keyResults.length && this.toggleKeyResults(id)}
                />
              </div>),
              ...keyResults.map(keyResult =>
                <KRBar key={keyResult.id} {...{ display, keyResult }} />),
            ];
          }))}
        </div>
        <div className={`${styles.add_okr} ${styles.alignC}`}>
          <button className={styles.addOkr} onClick={onAdd}>
            <span className={styles.circle}>
              <img src="/img/common/icn_plus.png" alt="Add" />
            </span>
            <span>新しい目標を追加</span>
          </button>
        </div>
      </div>);
  }
}
