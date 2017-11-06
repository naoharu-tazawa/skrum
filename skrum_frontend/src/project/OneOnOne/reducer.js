import { Action } from './action';

export default (state = {
  notes: [],
  found: [],
  unread: {},
  isFetching: false,
  hasMoreNotes: false,
  isFetchingMore: false,
  isQuerying: false,
  hasMoreQueryNotes: false,
  isQueryingMore: false,
  isPostingNote: false,
}, { type: actionType, payload, error }) => {
  switch (actionType) {
    case Action.REQUEST_FETCH_ONE_ON_ONE_NOTES:
      return { ...state, isFetching: true };

    case Action.FINISH_FETCH_ONE_ON_ONE_NOTES: {
      if (error) {
        const { message } = payload;
        return { ...state,
          ...{ notes: [], isFetching: false, hasMoreNotes: false, error: { message } } };
      }
      const { notes, type } = payload.data;
      const hasMoreNotes = notes.length > 0;
      return { ...state, notes, type, isFetching: false, hasMoreNotes, error: null };
    }

    case Action.REQUEST_MORE_ONE_ON_ONE_NOTES:
      return { ...state, isFetchingMore: true };

    case Action.FINISH_MORE_ONE_ON_ONE_NOTES: {
      if (error) {
        const { message } = payload;
        return { ...state, isFetchingMore: false, hasMoreNotes: false, error: { message } };
      }
      const { notes: newNotes } = payload.data;
      const hasMoreNotes = newNotes.length > 0;
      const notes = [...state.notes, ...newNotes];
      return { ...state, notes, isFetchingMore: false, hasMoreNotes, error: null };
    }

    case Action.REQUEST_FETCH_ONE_ON_ONE_QUERY:
      return { ...state, isQuerying: true };

    case Action.FINISH_FETCH_ONE_ON_ONE_QUERY: {
      if (error) {
        const { message } = payload;
        return { ...state,
          ...{ found: [], isQuerying: false, hasMoreQueryNotes: false, error: { message } } };
      }
      const { data: found, query, unreadFlgCounts: unread = state.unread } = payload.data;
      const hasMoreQueryNotes = found.length > 0;
      return { ...state, found, query, unread, isQuerying: false, hasMoreQueryNotes, error: null };
    }

    case Action.REQUEST_MORE_ONE_ON_ONE_QUERY:
      return { ...state, isQueryingMore: true };

    case Action.FINISH_MORE_ONE_ON_ONE_QUERY: {
      if (error) {
        const { message } = payload;
        return { ...state, isQueryingMore: false, hasMoreQueryNotes: false, error: { message } };
      }
      const { data } = payload.data;
      const hasMoreQueryNotes = data.length > 0;
      const found = [...state.found, ...data];
      return { ...state, found, isQueryingMore: false, hasMoreQueryNotes, error: null };
    }

    case Action.REQUEST_POST_ONE_ON_ONE_NOTE:
      return { ...state, isPostingNote: true };

    case Action.FINISH_POST_ONE_ON_ONE_NOTE: {
      if (error) {
        return { ...state, isPostingNote: false, error: { message: payload.message } };
      }
      return { ...state, isPostingNote: false, error: null };
    }

    default:
      return state;
  }
};
