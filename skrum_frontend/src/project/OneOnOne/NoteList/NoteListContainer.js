import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { notesPropTypes } from '../propTypes';
import NoteList from './NoteList';
import { mapOneOnOne } from '../../../util/OneOnOneUtil';

class NoteListContainer extends Component {

  static propTypes = {
    notes: notesPropTypes.isRequired,
    type: PropTypes.string.isRequired,
    isFetching: PropTypes.bool.isRequired,
    hasMoreNotes: PropTypes.bool.isRequired,
    isFetchingMore: PropTypes.bool.isRequired,
    dispatchFetchMoreNotes: PropTypes.func.isRequired,
  };

  render() {
    const { type, notes, isFetching, hasMoreNotes, isFetchingMore,
      dispatchFetchMoreNotes } = this.props;
    return (
      <NoteList
        {...{ type, notes, isFetching, hasMoreNotes, isFetchingMore, dispatchFetchMoreNotes }}
      />);
  }
}

const mapStateToProps = ({ oneOnOne }) => {
  const { isFetching, hasMoreNotes, isFetchingMore } = oneOnOne;
  const notes = oneOnOne.notes.map(mapOneOnOne);
  return { notes, isFetching, hasMoreNotes, isFetchingMore };
};

export default connect(
  mapStateToProps,
)(NoteListContainer);
