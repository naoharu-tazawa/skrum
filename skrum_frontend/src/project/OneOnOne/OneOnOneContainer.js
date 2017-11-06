import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { toPairs } from 'lodash';
import { queryPropTypes } from './propTypes';
import NoteListContainer from './NoteList/NoteListContainer';
import NoteQueryContainer from './NoteQuery/NoteQueryContainer';
import { errorType } from '../../util/PropUtil';
import { getDate, toUtcDate } from '../../util/DatetimeUtil';
import { isPathFinal } from '../../util/RouteUtil';
import { fetchOneOnOneNotes, fetchMoreOneOnOneNotes, queryOneOnOneNotes, queryMoreOneOnOneNotes } from './action';
import styles from './OneOnOneContainer.css';

class OneOnOneContainer extends Component {

  static propTypes = {
    type: PropTypes.string.isRequired,
    query: queryPropTypes.isRequired,
    unread: PropTypes.shape({}).isRequired,
    pathname: PropTypes.string.isRequired,
    dispatchFetchNotes: PropTypes.func.isRequired,
    dispatchFetchMoreNotes: PropTypes.func.isRequired,
    dispatchQueryNotes: PropTypes.func.isRequired,
    dispatchQueryMoreNotes: PropTypes.func.isRequired,
    error: errorType,
  };

  componentWillMount() {
    const { pathname, type, query, dispatchFetchNotes, dispatchQueryNotes } = this.props;
    if (isPathFinal(pathname)) {
      dispatchFetchNotes(type);
      dispatchQueryNotes(query);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname, type, query, dispatchFetchNotes, dispatchQueryNotes } = next;
    if (this.props.pathname !== pathname) {
      dispatchFetchNotes(type);
      dispatchQueryNotes(query);
    }
  }

  render() {
    const { type, query, unread, dispatchFetchNotes, dispatchFetchMoreNotes,
      dispatchQueryNotes, dispatchQueryMoreNotes } = this.props;
    const typeMap = {
      dailyReport: '日報',
      progressMemo: '進捗報告',
      hearing: 'ヒヤリング',
      feedback: 'フィードバック',
      interviewNote: '面談メモ',
    };
    return (
      <article className={styles.container}>
        <section className={styles.main}>
          <nav>
            <ul>
              {toPairs(typeMap).map(([key, name], index) => (
                <li key={key}>
                  <a
                    className={`${type === `${index + 1}` ? styles.selected : ''}`}
                    onClick={() => dispatchFetchNotes(`${index + 1}`)}
                    tabIndex={0}
                  >
                    <span className={styles.type}>{name}</span>
                    <span className={styles.countBox}>
                      {unread[key] ? <span className={styles.count}>{unread[key]}</span> : null}
                    </span>
                  </a>
                </li>
              ))}
            </ul>
          </nav>
          <NoteListContainer
            {...{ type, dispatchFetchMoreNotes }}
          />
        </section>
        <NoteQueryContainer
          {...{ query, dispatchQueryNotes, dispatchQueryMoreNotes }}
        />
      </article>);
  }
}

const mapStateToProps = ({ auth, oneOnOne, routing }) => {
  const { userId: currentUserId } = auth;
  const today = toUtcDate(getDate());
  const defaultQuery = { startDate: today, endDate: today, keyword: '' };
  const { type = '1', query = defaultQuery, unread, hasMoreNotes, error } = oneOnOne;
  const { locationBeforeTransitions } = routing;
  const { pathname } = locationBeforeTransitions || {};
  return { currentUserId, type, query, unread, hasMoreNotes, error, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchNotes = (userId, type) =>
    dispatch(fetchOneOnOneNotes(userId, type));
  const dispatchFetchMoreNotes = (userId, type, before) =>
    dispatch(fetchMoreOneOnOneNotes(userId, type, before));
  const dispatchQueryNotes = (userId, query) =>
    dispatch(queryOneOnOneNotes(userId, query));
  const dispatchQueryMoreNotes = (userId, query, before) =>
    dispatch(queryMoreOneOnOneNotes(userId, query, before));
  return {
    dispatchFetchNotes,
    dispatchFetchMoreNotes,
    dispatchQueryNotes,
    dispatchQueryMoreNotes,
  };
};

const mergeProps = ({ currentUserId, ...state }, {
  dispatchFetchNotes,
  dispatchFetchMoreNotes,
  dispatchQueryNotes,
  dispatchQueryMoreNotes,
}, props) => ({
  ...state,
  ...props,
  dispatchFetchNotes: type =>
    dispatchFetchNotes(currentUserId, type),
  dispatchFetchMoreNotes: (type, before) =>
    dispatchFetchMoreNotes(currentUserId, type, before),
  dispatchQueryNotes: query =>
    dispatchQueryNotes(currentUserId, query),
  dispatchQueryMoreNotes: (query, before) =>
    dispatchQueryMoreNotes(currentUserId, query, before),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(OneOnOneContainer);
