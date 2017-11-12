import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router';
import { toPairs } from 'lodash';
import { notesPropTypes, feedbackTypes } from '../propTypes';
import NoteDialogBar from './NoteDialogBar';
import EntityLink from '../../../components/EntityLink';
import { EntityType } from '../../../util/EntityUtil';
import { isValidDate, formatDate } from '../../../util/DatetimeUtil';
import styles from './NoteDialogList.css';

const headerMap = {
  okrName: '対象目標',
  targetDate: '対象日付',
  dueDate: '回答期限',
  feedbackType: 'フィードバック種別',
  intervieweeUserName: '面談相手',
  interviewDate: '面談日',
};

const mapHeader = (header, mapper) =>
  toPairs(header).map(([key, value]) => {
    const label = headerMap[key];
    if (key === 'feedbackType') {
      return mapper(key, label, feedbackTypes[value]);
    }
    if (isValidDate(value)) {
      return mapper(key, label, formatDate(value));
    }
    return mapper(key, label, value);
  });

export default class NoteDialogList extends Component {

  static propTypes = {
    backPath: PropTypes.string.isRequired,
    currentUserId: PropTypes.number.isRequired,
    header: PropTypes.shape({}).isRequired,
    notes: notesPropTypes.isRequired,
    isFetching: PropTypes.bool.isRequired,
    isPostingReply: PropTypes.bool.isRequired,
    dispatchPostOneOnOneReply: PropTypes.func.isRequired,
  };

  render() {
    const { backPath, currentUserId, header, notes, isFetching, isPostingReply,
      dispatchPostOneOnOneReply } = this.props;
    const { reply = '' } = this.state || {};
    const sender = { type: EntityType.USER, id: currentUserId };
    return (
      <section className={styles.content}>
        <header>
          <h1>
            <Link className={styles.backLink} to={backPath}>一覧へ戻る</Link>
            <span className={styles.title}>
              {!isFetching && mapHeader(header, (key, label, text) =>
                (label ? <p key={key}>{label}：{text}</p> : null))}
            </span>
          </h1>
        </header>
        {isFetching && <span className={styles.spinner} />}
        {!isFetching && notes.map(note => <NoteDialogBar key={note.id} note={note} />)}
        <footer>
          <section>
            <EntityLink className={styles.icon} avatarOnly avatarSize={34} entity={sender} local />
            <textarea
              placeholder="コメントをする …"
              value={reply}
              onChange={({ target }) => this.setState({ reply: target.value })}
            />
          </section>
          <button
            disabled={!reply || isPostingReply}
            onClick={() => dispatchPostOneOnOneReply(reply)
              .then(({ error }) => !error && this.setState({ reply: '' }))}
          >
            {isPostingReply ? <div className={styles.spinner} /> : 'コメントする'}
          </button>
        </footer>
      </section>);
  }
}
