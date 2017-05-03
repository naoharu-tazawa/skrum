import React, { Component } from 'react';
import { okrsPropTypes } from './propTypes';
import OKRBar from './OKRBar';
import styles from './OKRList.css';

export default class OKRList extends Component {

  static propTypes = {
    items: okrsPropTypes.isRequired,
  };

  render() {
    const { items } = this.props;
    return (
      <div className={styles.component}>
        <div className={styles.okrHeader}>
          <OKRBar header />
        </div>
        <div className={styles.okrBars}>
          {items.map(okr =>
            <div key={okr.id} className={styles.okrBar}>
              <OKRBar okr={okr} />
            </div>)}
        </div>
      </div>);
  }
}
