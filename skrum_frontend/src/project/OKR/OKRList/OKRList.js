import React, { Component } from 'react';
import _ from 'lodash';
import { okrsPropTypes } from './propTypes';
import OKRBar from './OKRBar';
import styles from './OKRList.css';

export default class OKRList extends Component {

  static propTypes = {
    items: okrsPropTypes.isRequired,
  };

  state = {
    openedKeyResults: {},
  };

  toggleKeyResults(id) {
    const { openedKeyResults } = this.state;
    const { [id]: opened = false } = openedKeyResults;
    this.setState({ openedKeyResults: { ...openedKeyResults, [id]: !opened } });
  }

  render() {
    const { items } = this.props;
    const { openedKeyResults } = this.state;
    const mapKeyResults = keyResult => (
      <a
        key={keyResult.id}
        tabIndex={keyResult.id}
        className={styles.okrBar}
      >
        <OKRBar keyResult={keyResult} />
      </a>);
    return (
      <div className={styles.component}>
        <div className={styles.okrHeader}>
          <OKRBar header />
        </div>
        <div className={styles.okrBars}>
          {_.flatten(items.map((okr) => {
            return [
              (<a
                key={okr.id}
                tabIndex={okr.id}
                className={styles.okrBar}
                onClick={() => { if (okr.keyResults.length > 0) this.toggleKeyResults(okr.id); }}
              >
                <OKRBar okr={okr} />
              </a>),
              ...(!openedKeyResults[okr.id] ? [] : okr.keyResults.map(mapKeyResults)),
            ];
          }))}
        </div>
      </div>);
  }
}
