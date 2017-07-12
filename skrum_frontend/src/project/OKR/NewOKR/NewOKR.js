import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field, getFormValues, SubmissionError } from 'redux-form';
import { isEmpty, toNumber, toLower, partial } from 'lodash';
import { okrPropTypes } from '../../OKRDetails/propTypes';
import DialogForm from '../../../dialogs/DialogForm';
import OwnerSearch, { ownerPropType } from '../../OwnerSearch/OwnerSearch';
import TimeframesDropdown from '../../../components/TimeframesDropdown';
import DatePickerInput from '../../../components/DatePickerInput';
import OKRSearch from '../../OKRSearch/OKRSearch';
import { withLoadedReduxForm, withItemisedReduxField, withSelectReduxField, withReduxField } from '../../../util/FormUtil';
import { getOwnerTypeId, getOwnerTypeSubject } from '../../../util/OwnerUtil';
import { explodePath } from '../../../util/RouteUtil';
import { isValidDate, compareDates, toUtcDate } from '../../../util/DatetimeUtil';
import { postOkr } from '../action';
import { postKR } from '../../OKRDetails/action';
import styles from './NewOKR.css';

const formName = 'newOKR';

const validate = ({ owner, okrName, startDate, endDate } = {}) => {
  return {
    owner: isEmpty(owner) && '担当者を入力してください',
    okrName: !okrName && '目標名を入力してください',
    startDate: !isValidDate(startDate) && '開始日を入力してください',
    endDate: !isValidDate(endDate) && '終了日を入力してください',
  };
};

const okrDialogInitialValues = (state) => {
  const { company = {} } = state.top.data || {};
  const { defaultDisclosureType } = company.policy || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { timeframeId } = explodePath(pathname);
  return { disclosureType: defaultDisclosureType, timeframeId };
};

const OkrDialogForm = withLoadedReduxForm(
  DialogForm,
  formName,
  okrDialogInitialValues,
  { validate },
);

const KRDialogForm = withLoadedReduxForm(
  DialogForm,
  formName,
  (state) => {
    const { locationBeforeTransitions } = state.routing || {};
    const { pathname } = locationBeforeTransitions || {};
    const { subject, id } = explodePath(pathname);
    const owner = { type: getOwnerTypeId(subject), id };
    return { owner, ...okrDialogInitialValues(state) };
  },
  { validate },
);

const ownerSearch = withItemisedReduxField(OwnerSearch, 'owner');

class NewOKR extends Component {

  static propTypes = {
    type: PropTypes.oneOf(['Okr', 'KR']).isRequired,
    ownerName: PropTypes.string,
    parentOkr: okrPropTypes,
    onClose: PropTypes.func,
    subject: PropTypes.string,
    id: PropTypes.number,
    owner: ownerPropType,
    timeframeId: PropTypes.number,
    dispatchPostOkr: PropTypes.func,
    dispatchPostKR: PropTypes.func,
  };

  submit(entry) {
    const { type, parentOkr = {}, onClose, subject, id,
      dispatchPostOkr, dispatchPostKR } = this.props;
    const defaultOwner = { type: getOwnerTypeId(subject), id };
    const { owner = defaultOwner, timeframeId, disclosureType, okrName, okrDetail,
      targetValue, unit, startDate, endDate, alignment = {} } = entry;
    const ownerTypeSubject = getOwnerTypeSubject(owner.type);
    const ownerSubject = `owner${ownerTypeSubject}`;
    const isOwnerCurrent = toLower(ownerTypeSubject) === subject && owner.id === id;
    if (compareDates(startDate, endDate) > 0) {
      throw new SubmissionError({ _error: '終了日は開始日以降に設定してください' });
    }
    const { type: parentOwnerType, id: parentOwnerId } = parentOkr.owner || {};
    const okrType = type === 'Okr' || owner.type !== parentOwnerType || owner.id !== parentOwnerId ? '1' : '2';
    const okr = {
      okrType,
      timeframeId,
      parentOkrId: parentOkr.id || alignment.id,
      ownerType: owner.type,
      [`${ownerSubject}Id`]: owner.id,
      disclosureType,
      okrName,
      okrDetail: okrDetail || '',
      targetValue: targetValue ? toNumber(targetValue) : 100,
      unit: unit || '%',
      startDate: toUtcDate(startDate),
      endDate: toUtcDate(endDate),
    };
    const dispatcher = okrType === '1' ? partial(dispatchPostOkr, subject, isOwnerCurrent) : dispatchPostKR;
    this.setState({ isSubmitting: true }, () =>
      dispatcher(okr).then(({ error }) =>
        this.setState({ isSubmitting: false }, () => !error && onClose()),
      ),
    );
  }

  render() {
    const { type, owner, ownerName, parentOkr, timeframeId, onClose } = this.props;
    const { isSubmitting = false } = this.state || {};
    const disclosureTypes = [
      { value: '1', label: '全体' },
      { value: '2', label: 'グループ' },
      { value: '3', label: '管理者' },
      { value: '4', label: 'グループ管理者' },
      { value: '5', label: '本人のみ' },
    ];
    const okrForm = Form => (
      <Form
        title={type === 'Okr' ? '目標新規登録' : 'サブ目標新規登録'}
        submitButton="目標作成"
        onSubmit={this.submit.bind(this)}
        isSubmitting={isSubmitting}
        onClose={onClose}
      >
        <div className={styles.dialog}>
          {parentOkr && (
            <div className={styles.parentOkrBox}>
              紐付け先目標
              <div className={styles.parentOkr}>
                <div className={styles.parentOkrOwnerBox}>
                  <div className={styles.parentOkrOwnerImage} />
                  <div className={styles.parentOkrOwnerName}>{parentOkr.owner.name}</div>
                </div>
                <div className={styles.parentOkrName}>{parentOkr.name}</div>
              </div>
            </div>)}
          <div className={styles.ownerTimeframesBox}>
            <div className={styles.ownerBox}>
              <span className={styles.label}>担当者</span>
              {ownerName ? <span className={styles.label}>{ownerName}</span> : ownerSearch}
              {type === 'Okr' && <span className={styles.label}>目標の時間枠</span>}
              {type === 'Okr' && withSelectReduxField(TimeframesDropdown, 'timeframeId',
                { styleNames: {
                  base: styles.timePeriod,
                  item: styles.timeframe,
                  current: styles.timeframeCurrent,
                } },
              )}
            </div>
          </div>
          <div className={styles.disclosureType}>
            <span className={styles.label}>公開範囲</span>
            {disclosureTypes.map(({ value, label }) => (
              <label key={value}>
                <Field name="disclosureType" component="input" type="radio" value={value} />
                {label}
              </label>
            ))}
          </div>
          <Field component="textarea" name="okrName" placeholder="目標120字以内" maxLength={120} />
          <Field component="textarea" name="okrDetail" placeholder="詳細250字以内" maxLength={250} />
          <div className={styles.progressBox}>
            <span className={styles.label}>目標値</span>
            <div className={styles.inputWithHint}>
              <Field component="input" type="number" name="targetValue" />
              <span className={styles.hint}>※空欄の場合は100</span>
            </div>
            <span className={styles.label}>単位</span>
            <div className={styles.inputWithHint}>
              <Field component="input" name="unit" placeholder="例)時間、回、件、枚" />
              <span className={styles.hint}>※途中で変更不可。空欄の場合は%</span>
            </div>
          </div>
          <div className={styles.dateRangeBox}>
            <span className={styles.label}>開始日</span>
            {withReduxField(DatePickerInput, 'startDate')}
            <span className={styles.label}>期限日</span>
            {withReduxField(DatePickerInput, 'endDate')}
          </div>
          {type === 'Okr' && timeframeId && <div className={styles.alignmentBox}>
            <span className={styles.label}>紐づけ先検索</span>
            {withItemisedReduxField(OKRSearch, 'alignment',
              { owner, timeframeId, disabled: !ownerName && isEmpty(owner) })}
          </div>}
        </div>
      </Form>);
    return okrForm(type === 'Okr' ? OkrDialogForm : KRDialogForm);
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject, id } = explodePath(pathname);
  const { owner, timeframeId } = getFormValues(formName)(state) || {};
  return { subject, id, owner, timeframeId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostOkr = (subject, id, entry) =>
    dispatch(postOkr(subject, id, entry));
  const dispatchPostKR = entry =>
    dispatch(postKR(entry));
  return { dispatchPostOkr, dispatchPostKR };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NewOKR);
