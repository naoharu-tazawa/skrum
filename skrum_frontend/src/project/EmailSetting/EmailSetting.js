import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { toPairs } from 'lodash';
import { toastr } from 'react-redux-toastr';
import styles from './EmailSetting.css';

const settingMapping = {
  okrAchievement: '達成率通知メール',
  okrTimeline: 'タイムライン投稿通知メール',
  okrDeadlineReminder: '目標期限日通知メール',
  okrReminder: '進捗登録リマインドメール',
  reportMemberAchievement: 'メンバー進捗状況レポートメール',
  reportFeedbackTarget: 'フィードバック対象者通知メール',
  serviceNotification: 'サービスお知らせメール',
  reportGroupAchievement: 'グループ進捗状況レポートメール',
};

export default class EmailSetting extends Component {

  static propTypes = {
    userId: PropTypes.number.isRequired,
    data: PropTypes.shape({}).isRequired,
    isPutting: PropTypes.bool.isRequired,
    dispatchChangeEmailSettings: PropTypes.func.isRequired,
  };

  render() {
    const { userId, data, isPutting, dispatchChangeEmailSettings } = this.props;
    const settings = { ...data, ...this.state };
    return (
      <div className={styles.component}>
        <div className={styles.setting}>
          <div className={styles.name} />
          <div className={styles.radio}>受信する</div>
          <div className={styles.radio}>受信しない</div>
        </div>
        {toPairs(settings).map(([setting, value]) => (
          <div key={setting} className={styles.setting}>
            <div className={styles.name}>{settingMapping[setting]}</div>
            <div className={styles.radio}>
              <input
                type="radio"
                name={setting}
                defaultChecked={value === 1}
                onClick={() => this.setState({ [setting]: 1 })}
              />
            </div>
            <div className={styles.radio}>
              <input
                type="radio"
                name={setting}
                defaultChecked={value === 0}
                onClick={() => this.setState({ [setting]: 0 })}
              />
            </div>
          </div>
        ))}
        <div className={styles.setting}>
          <div className={styles.name} />
          {isPutting ?
            <div className={styles.spinner} /> :
            <button
              className={styles.button}
              onClick={() => dispatchChangeEmailSettings(userId, settings)
                .then(({ error /* , payload: { message } = {} */ } = {}) =>
                  (error ? toastr.error('保存に失敗しました') : toastr.info('設定が完了しました')),
                )}
            >
              保存する
            </button>}
        </div>
      </div>);
  }
}
