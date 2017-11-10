import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { notesPropTypes } from '../propTypes';
import NoteDialogList from './NoteDialogList';
import { fetchOneOnOneDialog, postOneOnOneReply } from '../action';
import { mapOneOnOne } from '../../../util/OneOnOneUtil';
import { explodePath, isPathFinal, toBasicPath } from '../../../util/RouteUtil';

class NoteDialogContainer extends Component {

  static propTypes = {
    currentUserId: PropTypes.number.isRequired,
    header: PropTypes.shape({}).isRequired,
    notes: notesPropTypes.isRequired,
    isFetching: PropTypes.bool.isRequired,
    isPostingReply: PropTypes.bool.isRequired,
    pathname: PropTypes.string.isRequired,
    dispatchFetchOneOnOneDialog: PropTypes.func.isRequired,
    dispatchPostOneOnOneReply: PropTypes.func.isRequired,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchDialog(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchDialog(pathname);
    }
  }

  fetchDialog(pathname) {
    const { dispatchFetchOneOnOneDialog } = this.props;
    const { aspect, aspectId } = explodePath(pathname);
    if (aspect === 'd') {
      dispatchFetchOneOnOneDialog(aspectId);
    }
  }

  render() {
    const { currentUserId, header, notes, isFetching, isPostingReply, pathname,
      dispatchPostOneOnOneReply } = this.props;
    const backPath = toBasicPath(pathname);
    return (
      <NoteDialogList
        {...{ backPath, currentUserId, header, notes, isFetching, isPostingReply }}
        {...{ dispatchPostOneOnOneReply }}
      />);
  }
}

const mapStateToProps = ({ auth, routing, oneOnOne }) => {
  const { userId: currentUserId } = auth;
  const { locationBeforeTransitions } = routing;
  const { pathname } = locationBeforeTransitions || {};
  const { aspectId: id } = explodePath(pathname);
  const { header, dialog, isFetchingDialog: isFetching, isPostingReply } = oneOnOne;
  const notes = dialog.map(mapOneOnOne);
  return { currentUserId, id, header, notes, isFetching, isPostingReply, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchOneOnOneDialog = (userId, id) =>
    dispatch(fetchOneOnOneDialog(userId, id));
  const dispatchPostOneOnOneReply = (id, reply) =>
    dispatch(postOneOnOneReply(id, reply));
  return {
    dispatchFetchOneOnOneDialog,
    dispatchPostOneOnOneReply,
  };
};

const mergeProps = (state, {
  dispatchFetchOneOnOneDialog,
  dispatchPostOneOnOneReply,
}, props) => ({
  ...state,
  ...props,
  dispatchFetchOneOnOneDialog: id =>
    dispatchFetchOneOnOneDialog(state.currentUserId, id),
  dispatchPostOneOnOneReply: reply =>
    dispatchPostOneOnOneReply(state.id, reply),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(NoteDialogContainer);
