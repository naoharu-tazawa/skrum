import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { timeframePropTypes } from './propTypes';
import InlineTextInput from '../../../editors/InlineTextInput';
import InlineDateInput from '../../../editors/InlineDateInput';
import ConfirmationPrompt from '../../../dialogs/ConfirmationPrompt';
import { withModal } from '../../../util/ModalUtil';
import { formatDate, compareDates } from '../../../util/DatetimeUtil';
import styles from './TimeframeBar.css';

class TimeframeBar extends Component {

  static propTypes = {
    header: PropTypes.bool,
    timeframe: timeframePropTypes,
    dispatchPutTimeframe: PropTypes.func,
    dispatchDefaultTimeframe: PropTypes.func,
    dispatchDeleteTimeframe: PropTypes.func,
    openModal: PropTypes.func,
  };

  timeframeBox = ({ name, startDate, endDate }) => (
    <div className={styles.box}>
      <div className={styles.name}>名前<p>{name}</p></div>
      <div className={styles.date}>開始日<p>{formatDate(startDate)}</p></div>
      <div className={styles.date}>終了日<p>{formatDate(endDate)}</p></div>
    </div>);

  defaultTimeframe = ({ id, name, startDate, endDate, onClose }) => (
    <ConfirmationPrompt
      title="デフォルト目標期間の変更"
      prompt="こちらの目標期間をデフォルトに設定しますか？"
      confirmButton="変更"
      onConfirm={() => this.props.dispatchDefaultTimeframe(id)}
      onClose={onClose}
    >
      {this.timeframeBox({ name, startDate, endDate })}
    </ConfirmationPrompt>);

  deleteTimeframe = ({ id, name, startDate, endDate, onClose }) => (
    <ConfirmationPrompt
      title="目標期間の削除"
      prompt="こちらの目標期間を削除しますか？"
      warning={(
        <ul>
          <li>一度削除した目標期間は復元できません。</li>
          <li>上記の目標期間に設定されている全ての目標も同時に削除されます。</li>
        </ul>
      )}
      confirmButton="削除"
      onConfirm={() => this.props.dispatchDeleteTimeframe(id)}
      onClose={onClose}
    >
      {this.timeframeBox({ name, startDate, endDate })}
    </ConfirmationPrompt>);

  render() {
    const { header, timeframe, dispatchPutTimeframe, openModal } = this.props;
    if (header) {
      return (
        <div className={styles.header}>
          <div className={styles.name}>名前</div>
          <div className={styles.date}>開始日</div>
          <div className={styles.date}>終了日</div>
          <div className={styles.default}>デフォルト設定</div>
          <div className={styles.delete} />
        </div>);
    }
    const { id, name, startDate, endDate, defaultFlg } = timeframe;
    return (
      <div className={styles.bar}>
        <div className={styles.name}>
          <InlineTextInput
            value={name}
            required
            onSubmit={value => dispatchPutTimeframe(id, { timeframeName: value })}
          />
        </div>
        <div className={styles.date}>
          <InlineDateInput
            value={startDate}
            required
            validate={value => compareDates(value, endDate) > 0 && '終了日は開始日以降に設定してください'}
            onSubmit={value => dispatchPutTimeframe(id, { startDate: value })}
          />
        </div>
        <div className={styles.date}>
          <InlineDateInput
            value={endDate}
            required
            validate={value => compareDates(startDate, value) > 0 && '終了日は開始日以降に設定してください'}
            onSubmit={value => dispatchPutTimeframe(id, { endDate: value })}
          />
        </div>
        <div className={styles.default}>
          {defaultFlg && <div className={styles.check} />}
          {!defaultFlg && (
            <button onClick={() => openModal(this.defaultTimeframe, timeframe)}>
              <div className={styles.check_inactive} />
            </button>
          )}
        </div>
        {!defaultFlg && (
          <button
            className={styles.delete}
            onClick={() => openModal(this.deleteTimeframe, timeframe)}
          >
            <img src="/img/delete.png" alt="" />
          </button>)}
      </div>);
  }
}

export default withModal(TimeframeBar);
