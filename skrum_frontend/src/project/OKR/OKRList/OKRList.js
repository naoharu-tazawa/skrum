import React, { Component } from 'react';
import _ from 'lodash';
import { okrsPropTypes, keyResultsPropTypes } from './propTypes';
import OKRBar from './OKRBar';
import styles from './OKRList.css';

export default class OKRList extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
    keyResults: keyResultsPropTypes,
  };

  state = {
    expandedKeyResults: {},
  };

  toggleKeyResults(id) {
    const { expandedKeyResults } = this.state;
    const { [id]: expanded = false } = expandedKeyResults;
    this.setState({ expandedKeyResults: { ...expandedKeyResults, [id]: !expanded } });
  }

  render() {
    const { okrs = [], keyResults = [] } = this.props;
    const { expandedKeyResults } = this.state;
    const mapKeyResult = ({ display, okr, keyResult }) =>
      <OKRBar key={keyResult.id} {...{ display, okr, keyResult }} />;
    return (
      <div className={styles.component}>
        <div className={styles.okrHeader}>
          <OKRBar display={okrs.length ? 'o-header' : 'kr-header'} />
        </div>
        <div className={styles.bars}>
          {_.flatten(okrs.map((okr) => {
            const { id, keyResults: okrKeyResults = [] } = okr;
            const display = expandedKeyResults[id] ? 'expanded' : 'collapsed';
            return [
              (<a
                key={id}
                tabIndex={id}
                className={styles.bar}
                onClick={() => { if (okrKeyResults.length > 0) this.toggleKeyResults(id); }}
              >
                <OKRBar display="normal" okr={okr} />
              </a>),
              ...(okrKeyResults.map(keyResult => mapKeyResult({ display, okr, keyResult }))),
            ];
          }))}
          {keyResults.map(keyResult => mapKeyResult({ display: 'normal', keyResult }))}
        </div>
      </div>);
  }
}
