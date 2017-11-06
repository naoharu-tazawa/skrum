import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Linkify from 'react-linkify';
import { notePropTypes } from '../propTypes';
import EntityLink from '../../../components/EntityLink';
import { formatDate, DateFormat, toRelativeTimeText } from '../../../util/DatetimeUtil';
import { withModal } from '../../../util/ModalUtil';
import styles from './NoteBar.css';

class NoteBar extends Component {

  static propTypes = {
    note: notePropTypes.isRequired,
    openModal: PropTypes.func.isRequired,
  };

  render() {
    const { note } = this.props;
    const { sender, toNames, lastUpdate, preview } = note;
    return (
      <section className={styles.content}>
        <EntityLink className={styles.icon} avatarOnly avatarSize={34} entity={sender} route={{ tab: 'objective' }} />
        <section className={styles.text}>
          <header>
            <div className={styles.title}>{sender.name}&emsp;｜&emsp;To: {toNames}</div>
            <div className={styles.date}>
              {formatDate(lastUpdate, { format: DateFormat.YMDHM })}
              &emsp;
              {toRelativeTimeText(lastUpdate)}
            </div>
          </header>
          <section className={styles.body}>
            <Linkify><p className={styles.preview}>{preview}</p></Linkify>
          </section>
        </section>
      </section>);
  }
}

export default withModal(NoteBar);
