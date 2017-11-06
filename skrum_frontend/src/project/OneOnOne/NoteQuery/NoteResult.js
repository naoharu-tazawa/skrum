import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { notePropTypes } from '../propTypes';
import EntityLink from '../../../components/EntityLink';
import { formatTime } from '../../../util/DatetimeUtil';
import { withModal } from '../../../util/ModalUtil';
import styles from './NoteResult.css';

class NoteResult extends Component {

  static propTypes = {
    note: notePropTypes.isRequired,
    isCurrent: PropTypes.bool,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { note, isCurrent } = this.props;
    const { type, sender, toNames, lastUpdate, preview } = note;
    const typeNameMap = { 1: '日報', 2: '進捗報告', 3: 'ヒアリング', 4: 'フィードバック', 5: '面談メモ' };
    const typeColorMap = { 1: '#f2b230', 2: '#2eb9fe', 3: '#ff4dff', 4: '#36b374', 5: '#b1e43e' };
    return (
      <section className={`${styles.content} ${isCurrent ? styles.current : ''}`}>
        <header>
          <EntityLink className={styles.icon} avatarOnly avatarSize={34} entity={sender} route={{ tab: 'objective' }} />
          <section className={styles.text}>
            <div className={styles.typeTime}>
              <div className={styles.type} style={{ backgroundColor: typeColorMap[type] }}>
                {typeNameMap[type]}
              </div>
              <div className={styles.time}>{formatTime(lastUpdate)}</div>
            </div>
            <div className={styles.name}>{sender.name}, {toNames}</div>
          </section>
        </header>
        <section className={styles.body}>{preview}</section>
      </section>);
  }
}

export default withModal(NoteResult);
