import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { toPairs, keys, toNumber } from 'lodash';
import { oneOnOneTypePropType, queryPropTypes, oneOnOneTypes } from './propTypes';
import NoteListContainer from './NoteList/NoteListContainer';
import NoteQueryContainer from './NoteQuery/NoteQueryContainer';
import NoteDialogContainer from './NoteDialog/NoteDialogContainer';
import NewOneOnOneNote from './NewOneOnOneNote/NewOneOnOneNote';
import { errorType } from '../../util/PropUtil';
import { getDate, toUtcDate } from '../../util/DatetimeUtil';
import { isPathFinal, comparePath, explodePath, implodePath } from '../../util/RouteUtil';
import { withModal } from '../../util/ModalUtil';
import { fetchOneOnOneNotes, fetchMoreOneOnOneNotes, queryOneOnOneNotes, queryMoreOneOnOneNotes } from './action';
import styles from './OneOnOneContainer.css';

class OneOnOneContainer extends Component {

  static propTypes = {
    currentUserId: PropTypes.number.isRequired,
    type: oneOnOneTypePropType.isRequired,
    query: queryPropTypes.isRequired,
    unread: PropTypes.shape({}).isRequired,
    pathname: PropTypes.string.isRequired,
    dispatchFetchNotes: PropTypes.func.isRequired,
    dispatchFetchMoreNotes: PropTypes.func.isRequired,
    dispatchQueryNotes: PropTypes.func.isRequired,
    dispatchQueryMoreNotes: PropTypes.func.isRequired,
    openModeless: PropTypes.func.isRequired,
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
    if (!comparePath(this.props.pathname, pathname, { basicOnly: true })) {
      dispatchFetchNotes(type);
      dispatchQueryNotes(query);
    }
  }

  render() {
    const { currentUserId, type, query, unread, pathname,
      dispatchFetchNotes, dispatchFetchMoreNotes,
      dispatchQueryNotes, dispatchQueryMoreNotes, openModeless } = this.props;
    const { aspect, aspectId: oooId, ...basicPath } = explodePath(pathname);
    const showDialog = aspect === 'd' && !!oooId;
    return (
      <article className={styles.container}>
        <section className={styles.main}>
          <nav>
            <ul>
              {toPairs(oneOnOneTypes).map(([key, name], index) => (
                <li key={key}>
                  <Link
                    to={implodePath(basicPath)}
                    className={`${type === `${index + 1}` ? styles.selected : ''}`}
                    onClick={() => dispatchFetchNotes(`${index + 1}`)}
                  >
                    <span className={styles.type}>{name}</span>
                    <span className={styles.countBox}>
                      {unread[key] ? <span className={styles.count}>{unread[key]}</span> : null}
                    </span>
                  </Link>
                </li>
              ))}
            </ul>
            <footer>
              <button
                className={styles.tool}
                onClick={() => openModeless(NewOneOnOneNote,
                  { types: keys(oneOnOneTypes)[toNumber(type) - 1],
                    ...{ userId: currentUserId, okr: {} } })}
              >
                <img src="/img/common/icn_plus.png" alt="Add" />
              </button>
            </footer>
          </nav>
          <article className={styles.list} style={showDialog ? { display: 'none' } : {}}>
            <NoteListContainer {...{ type, dispatchFetchMoreNotes }} />
          </article>
          {showDialog && (
            <article className={styles.list}>
              <NoteDialogContainer />
            </article>)}
        </section>
        <article className={styles.query}>
          <NoteQueryContainer {...{ query, dispatchQueryNotes, dispatchQueryMoreNotes }} />
        </article>
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

const mergeProps = (state, {
  dispatchFetchNotes,
  dispatchFetchMoreNotes,
  dispatchQueryNotes,
  dispatchQueryMoreNotes,
}, props) => ({
  ...state,
  ...props,
  dispatchFetchNotes: type =>
    dispatchFetchNotes(state.currentUserId, type),
  dispatchFetchMoreNotes: (type, before) =>
    dispatchFetchMoreNotes(state.currentUserId, type, before),
  dispatchQueryNotes: query =>
    dispatchQueryNotes(state.currentUserId, query),
  dispatchQueryMoreNotes: (query, before) =>
    dispatchQueryMoreNotes(state.currentUserId, query, before),
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(withModal(OneOnOneContainer, { wrapperClassName: styles.wrapper }));
