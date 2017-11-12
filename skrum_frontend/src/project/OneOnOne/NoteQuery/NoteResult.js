import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import { notePropTypes } from '../propTypes';
import EntityLink from '../../../components/EntityLink';
import { formatTime } from '../../../util/DatetimeUtil';
import { replacePath, explodePath } from '../../../util/RouteUtil';
import styles from './NoteResult.css';

class NoteResult extends Component {

  static propTypes = {
    note: notePropTypes.isRequired,
    isCurrent: PropTypes.bool.isRequired,
  };

  render() {
    const { note, isCurrent } = this.props;
    const { id, type, sender, toNames, lastUpdate, text, read } = note;
    const typeNameMap = { 1: '日報', 2: '進捗報告', 3: 'ヒアリング', 4: 'フィードバック', 5: '面談メモ' };
    const typeColorMap = { 1: '#f2b230', 2: '#2eb9fe', 3: '#ff4dff', 4: '#36b374', 5: '#b1e43e' };
    const path = replacePath({ aspect: 'd', aspectId: id });
    return (
      <section className={`${styles.content} ${isCurrent ? styles.current : ''} ${read ? '' : styles.unread}`}>
        <header>
          <EntityLink className={styles.icon} avatarOnly avatarSize={34} entity={sender} route={{ tab: 'objective' }} basicRoute />
          <Link className={styles.text} to={path}>
            <div className={styles.typeTime}>
              <div className={styles.type} style={{ backgroundColor: typeColorMap[type] }}>
                {typeNameMap[type]}
              </div>
              <div className={styles.time}>{formatTime(lastUpdate)}</div>
            </div>
            <div className={styles.name}>{sender.name}, {toNames}</div>
          </Link>
        </header>
        <Link className={styles.body} to={path}>{text}</Link>
      </section>);
  }
}

const mapStateToProps = ({ routing }, { note: { id } }) => {
  const { locationBeforeTransitions } = routing;
  const { pathname } = locationBeforeTransitions || {};
  const { aspect, aspectId } = explodePath(pathname);
  const currentDialogId = aspect === 'd' ? aspectId : 0;
  return { isCurrent: currentDialogId === id };
};

export default connect(
  mapStateToProps,
)(NoteResult);
