import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { last } from 'lodash';
import Waypoint from 'react-waypoint';
import { notesPropTypes } from '../propTypes';
import NoteBar from './NoteBar';
import styles from './NoteList.css';

export default class NoteList extends Component {

  static propTypes = {
    notes: notesPropTypes.isRequired,
    type: PropTypes.string.isRequired,
    isFetching: PropTypes.bool.isRequired,
    hasMoreNotes: PropTypes.bool.isRequired,
    isFetchingMore: PropTypes.bool.isRequired,
    dispatchFetchMoreNotes: PropTypes.func.isRequired,
  };

  render() {
    const { notes, type, isFetching, hasMoreNotes, isFetchingMore,
      dispatchFetchMoreNotes } = this.props;
    const lastNote = last(notes) || {};
    return (
      <section className={styles.notesBox}>
        {isFetching && <span className={styles.spinner} />}
        {!isFetching && notes.map(note => <NoteBar key={note.id} note={note} />)}
        {!isFetching && !isFetchingMore && hasMoreNotes && (
          <Waypoint onEnter={() => dispatchFetchMoreNotes(type, lastNote.lastUpdate)}>
            <div className={styles.spinner} />
          </Waypoint>)}
      </section>);
  }
}
