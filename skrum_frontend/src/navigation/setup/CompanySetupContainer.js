import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import Select from 'react-select';
import DatePickerInput from '../../components/DatePickerInput';
import { compareDates, formatUtcDate, toUtcDate, isValidDate } from '../../util/DatetimeUtil';
import { setupCompany } from '../action';
import styles from './Setup.css';

class CompanySetupContainer extends Component {
  static propTypes = {
    companyId: PropTypes.number.isRequired,
    isPosting: PropTypes.bool.isRequired,
    dispatchSetupCompany: PropTypes.func.isRequired,
  };

  handleSubmit(e, { user, company, timeframe }) {
    e.preventDefault();
    if (timeframe.endDate && compareDates(timeframe.startDate, timeframe.endDate) > 0) {
      this.setState({ error: '終了日は開始日以降に設定してください' });
    } else {
      const { companyId, dispatchSetupCompany } = this.props;
      dispatchSetupCompany(companyId, { user, company, timeframe })
        .then(({ error, payload: { message } = {} } = {}) => {
          if (error) { this.setState({ error: message }); }
          if (!error) { browserHistory.push('/'); }
        });
    }
  }

  render() {
    const { isPosting } = this.props;
    const { activeTab = 1, user = {}, company = { defaultDisclosureType: '1' }, timeframe = {},
      localCycleType, error } = this.state || {};
    return (
      <div className={styles.container}>
        <form
          className={styles.content}
          onSubmit={e => this.handleSubmit(e, { user, company, timeframe })}
        >
          <h1>会員登録</h1>
          <section>
            <ol>
              <li className={activeTab === 1 && styles.active}>プロフィール登録</li>
              <li className={activeTab === 2 && styles.active}>会社情報登録</li>
              <li className={activeTab === 3 && styles.active}>公開範囲設定</li>
              <li className={activeTab === 4 && styles.active}>目標期間登録</li>
            </ol>
          </section>
          <section className={activeTab !== 1 && styles.hidden}>
            <label>姓</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, lastName: e.target.value } })}
            />
            <label>名</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, firstName: e.target.value } })}
            />
          </section>
          <section className={activeTab !== 1 && styles.hidden}>
            <label>役職</label>
            <input
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, position: e.target.value } })}
            />
          </section>
          <section className={activeTab !== 1 && styles.hidden}>
            <label>電話番号</label>
            <input
              maxLength={45}
              onChange={e => this.setState({ user: { ...user, phoneNumber: e.target.value } })}
            />
          </section>
          <section className={activeTab !== 2 && styles.hidden}>
            <label>会社名</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ company: { ...company, name: e.target.value } })}
            />
          </section>
          <section className={activeTab !== 2 && styles.hidden}>
            <label>ヴィジョン</label>
            <input
              maxLength={120}
              onChange={e => this.setState({ company: { ...company, vision: e.target.value } })}
            />
          </section>
          <section className={activeTab !== 2 && styles.hidden}>
            <label>ミッション</label>
            <input
              maxLength={120}
              onChange={e => this.setState({ company: { ...company, mission: e.target.value } })}
            />
          </section>
          <div className={activeTab === 3 ? styles.disclosureTypes : styles.hidden}>
            <div className={styles.disclosureOption}>
              <label>
                <input
                  type="radio"
                  name="defaultDisclosureType"
                  defaultChecked
                  onChange={() => this.setState({ company: { ...company, defaultDisclosureType: '1' } })}
                />
                全体公開
              </label>
              全てのユーザに目標が公開されます。
            </div>
            <div className={styles.disclosureOption}>
              <label>
                <input
                  type="radio"
                  name="defaultDisclosureType"
                  onChange={() => this.setState({ company: { ...company, defaultDisclosureType: '2' } })}
                />
                グループ公開
              </label>
              所属するグループメンバーのみに目標が公開されます。（スーパー管理者は閲覧可能）
            </div>
            <div className={styles.disclosureOption}>
              <label>
                <input
                  type="radio"
                  name="defaultDisclosureType"
                  onChange={() => this.setState({ company: { ...company, defaultDisclosureType: '3' } })}
                />
                管理者公開
              </label>
              全ての管理者のみに目標が公開されます。（スーパー管理者は閲覧可能）
            </div>
            <div className={styles.disclosureOption}>
              <label>
                <input
                  type="radio"
                  name="defaultDisclosureType"
                  onChange={() => this.setState({ company: { ...company, defaultDisclosureType: '4' } })}
                />
                グループ管理者公開
              </label>
              所属するグループの管理者のみに目標が公開されます。（スーパー管理者は閲覧可能）
            </div>
          </div>
          <section className={activeTab !== 4 && styles.hidden}>
            <label>目標期間サイクル</label>
            <Select
              className={styles.select}
              options={[
                { value: '1', label: '1年ごと' },
                { value: '2', label: '6ヶ月ごと' },
                { value: '3', label: '3ヶ月ごと' },
                { value: '4', label: '1ヶ月ごと' },
                { value: 'c', label: 'カスタム設定' },
              ]}
              value={localCycleType}
              placeholder=""
              clearable={false}
              searchable={false}
              onChange={({ value }) =>
                this.setState({
                  localCycleType: value,
                  timeframe: {
                    ...timeframe,
                    cycleType: value === 'c' ? undefined : value,
                    customFlg: value === 'c',
                    ...(value !== 'c' && { timeframeName: undefined, endDate: undefined }),
                  } })}
            />
          </section>
          {timeframe.customFlg && <section className={activeTab !== 4 && styles.hidden}>
            <label>目標期間名</label>
            <input
              defaultValue={timeframe.timeframeName}
              maxLength={120}
              onChange={e =>
                this.setState({ timeframe: { ...timeframe, timeframeName: e.target.value } })}
            />
          </section>}
          <section className={activeTab !== 4 && styles.hidden}>
            <label>開始日</label>
            <DatePickerInput
              value={timeframe.startDate && formatUtcDate(timeframe.startDate)}
              onChange={({ target: { value } }) =>
                this.setState({ timeframe: { ...timeframe,
                  startDate: isValidDate(value) ? toUtcDate(value) : undefined } })}
              onDayClick={day =>
                this.setState({ timeframe: { ...timeframe, startDate: toUtcDate(day) } })}
            />
          </section>
          {timeframe.customFlg && <section className={activeTab !== 4 && styles.hidden}>
            <label>終了日</label>
            <DatePickerInput
              value={timeframe.endDate && formatUtcDate(timeframe.endDate)}
              onChange={({ target: { value } }) =>
                this.setState({ timeframe: { ...timeframe,
                  endDate: isValidDate(value) ? toUtcDate(value) : undefined } })}
              onDayClick={day =>
                this.setState({ timeframe: { ...timeframe, endDate: toUtcDate(day) } })}
            />
          </section>}
          {!isPosting && error && <div className={styles.error}>{error}</div>}
          <section>
            {!isPosting && (
              <button
                type="button"
                className={styles.back}
                disabled={activeTab === 1}
                onClick={() => this.setState({ activeTab: activeTab - 1, error: undefined })}
              >
                前へ
              </button>)}
            {!isPosting && activeTab < 4 && (
              <button
                type="button"
                disabled={
                  (activeTab === 1 && (!user.lastName || !user.firstName || !user.position)) ||
                  (activeTab === 2 && !company.name)}
                onClick={() => this.setState({ activeTab: activeTab + 1, error: undefined })}
              >
                次へ
              </button>)}
            {!isPosting && activeTab === 4 && (
              <button
                type="submit"
                disabled={
                  !localCycleType ||
                  (localCycleType === 'c' && !timeframe.timeframeName) ||
                  !timeframe.startDate ||
                  (localCycleType === 'c' && !timeframe.endDate)}
              >
                登録
              </button>)}
            {isPosting && <div className={styles.posting} />}
          </section>
        </form>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth;
  const { isPosting } = state.top;
  return { companyId, isPosting };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchSetupCompany = (companyId, setup) =>
    dispatch(setupCompany(companyId, setup));
  return { dispatchSetupCompany };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(CompanySetupContainer);
