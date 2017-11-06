import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { notesPropTypes, queryPropTypes } from '../propTypes';
import NoteQuery from './NoteQuery';
import { mapOneOnOne } from '../../../util/OneOnOneUtil';

class NoteQueryContainer extends Component {

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
    return (
      <NoteQuery
        {...{
          found,
          query,
          isQuerying,
          hasMoreQueryNotes,
          isQueryingMore,
          dispatchQueryNotes,
          dispatchQueryMoreNotes,
        }}
      />);
  }
}

const mapStateToProps = ({ oneOnOne }) => {
  const { isQuerying, hasMoreQueryNotes, isQueryingMore, error } = oneOnOne;
  const found = oneOnOne.found.map(mapOneOnOne);
  return { found, isQuerying, hasMoreQueryNotes, isQueryingMore, error };
};

export default connect(
  mapStateToProps,
)(NoteQueryContainer);
