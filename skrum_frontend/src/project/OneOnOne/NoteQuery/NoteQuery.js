import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { last } from 'lodash';
import Waypoint from 'react-waypoint';
import { notesPropTypes, queryPropTypes } from '../propTypes';
import DatePickerInput from '../../../editors/DatePickerInput';
import NoteResult from './NoteResult';
import { formatUtcDate, isValidDate, toUtcDate } from '../../../util/DatetimeUtil';
import styles from './NoteQuery.css';

export default class NoteQuery extends Component {

  static propTypes = {
    found: notesPropTypes.isRequired,
    query: queryPropTypes.isRequired,
    isQuerying: PropTypes.bool.isRequired,
    hasMoreQueryNotes: PropTypes.bool.isRequired,
    isQueryingMore: PropTypes.bool.isRequired,
    dispatchQueryNotes: PropTypes.func.isRequired,
    dispatchQueryMoreNotes: PropTypes.func.isRequired,
  };

  render() {
    const { found, query, isQuerying, hasMoreQueryNotes, isQueryingMore,
      dispatchQueryNotes, dispatchQueryMoreNotes } = this.props;
    const {
      startDate = query.startDate,
      endDate = query.endDate,
      keyword = query.keyword,
    } = this.state || {};
    const lastNote = last(found) || {};
    return (
      <aside className={styles.queryBox}>
        <header className={styles.search}>
          <DatePickerInput
            containerClass={styles.date}
            value={startDate && formatUtcDate(startDate)}
            onChange={({ target: { value } }) =>
              this.setState({ startDate: isValidDate(value) ? toUtcDate(value) : undefined })}
          />
          <span className={styles.span}>～</span>
          <DatePickerInput
            containerClass={styles.date}
            align="right"
            value={endDate && formatUtcDate(endDate)}
            onChange={({ target: { value } }) =>
              this.setState({ endDate: isValidDate(value) ? toUtcDate(value) : undefined })}
          />
          <input
            className={styles.user}
            type="text"
            placeholder="ユーザーを検索"
            value={keyword}
            onChange={({ target: { value } }) => this.setState({ keyword: value })}
          />
          <button
            className={`${styles.narrowing} ${isQuerying ? styles.spinner : ''}`}
            type="button"
            onClick={() => dispatchQueryNotes({ startDate, endDate, keyword })}
            disabled={isQuerying || !isValidDate(startDate) || !isValidDate(endDate)}
          >
            {!isQuerying ? '絞り込む' : '　'}
          </button>
        </header>
        <section className={styles.list}>
          {found.map(note => <NoteResult key={note.id} note={note} />)}
        </section>
        {!isQueryingMore && hasMoreQueryNotes && (
          <Waypoint onEnter={() => dispatchQueryMoreNotes(query, lastNote.lastUpdate)}>
            <div className={styles.spinner} />
          </Waypoint>)}
      </aside>);
  }
}
