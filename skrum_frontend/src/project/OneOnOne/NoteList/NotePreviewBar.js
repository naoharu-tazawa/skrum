import React, { Component } from 'react';
import { Link } from 'react-router';
import Linkify from 'react-linkify';
import { notePropTypes } from '../propTypes';
import EntityLink from '../../../components/EntityLink';
import { formatDate, DateFormat, toRelativeTimeText } from '../../../util/DatetimeUtil';
import { replacePath } from '../../../util/RouteUtil';
import styles from './NotePreviewBar.css';

export default class NotePreviewBar extends Component {

  static propTypes = {
    note: notePropTypes.isRequired,
  };

  render() {
    const { note } = this.props;
    const { id, sender, toNames, intervieweeUserName, lastUpdate, text, read } = note;
    return (
      <section className={`${styles.content} ${read ? '' : styles.unread}`}>
        <EntityLink className={styles.icon} avatarOnly avatarSize={34} entity={sender} route={{ tab: 'objective' }} />
        <Link className={styles.text} to={replacePath({ aspect: 'd', aspectId: id })}>
          <header>
            <div className={styles.title}>
              {sender.name}
              &emsp;｜&emsp;To: {toNames}
              &emsp;{intervieweeUserName && '｜'}
              &emsp;{intervieweeUserName && `面談相手: ${intervieweeUserName}`}
            </div>
            <div className={styles.date}>
              {formatDate(lastUpdate, { format: DateFormat.YMDHM })}
              &emsp;
              {toRelativeTimeText(lastUpdate)}
            </div>
          </header>
          <section className={styles.body}>
            <Linkify><p className={styles.body}>{text}</p></Linkify>
          </section>
        </Link>
      </section>);
  }
}
