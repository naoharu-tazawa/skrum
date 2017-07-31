import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field, getFormValues, SubmissionError } from 'redux-form';
import { isEmpty, omit, toNumber, partial } from 'lodash';
import { okrPropTypes } from '../../OKRDetails/propTypes';
import DialogForm from '../../../dialogs/DialogForm';
import OwnerSearch, { ownerPropType } from '../../OwnerSearch/OwnerSearch';
import TimeframesDropdown from '../../../components/TimeframesDropdown';
import DatePickerInput from '../../../components/DatePickerInput';
import EntitySubject from '../../../components/EntitySubject';
import DisclosureTypeOptions, { getDisclosureTypeName } from '../../../components/DisclosureTypeOptions';
import OKRSearch from '../../OKRSearch/OKRSearch';
import { withLoadedReduxForm, withItemisedReduxField, withSelectReduxField, withReduxField } from '../../../util/FormUtil';
import { getEntityTypeId } from '../../../util/EntityUtil';
import { getOwnerTypeSubject, mapOwnerOutbound } from '../../../util/OwnerUtil';
import { explodePath } from '../../../util/RouteUtil';
import { isValidDate, compareDates, toUtcDate } from '../../../util/DatetimeUtil';
import { OKRType } from '../../../util/OKRUtil';
import { postOkr } from '../action';
import { postKR } from '../../OKRDetails/action';
import styles from './NewOKR.css';

const formName = 'NewOKR';

const validate = ({ owner, disclosureType, okrName, startDate, endDate } = {}) => ({
  owner: isEmpty(owner) && '担当者を入力してください',
  disclosureType: owner && !getDisclosureTypeName(owner.type, disclosureType) && '公開範囲を入力してください',
  okrName: !okrName && '目標名を入力してください',
  startDate: !isValidDate(startDate) && '開始日を入力してください',
  endDate: !isValidDate(endDate) && '終了日を入力してください',
});

const dialogInitialValues = (state) => {
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
  dialogInitialValues,
  { validate },
);

const KRDialogForm = withLoadedReduxForm(
  DialogForm,
  formName,
  (state) => {
    const { locationBeforeTransitions } = state.routing || {};
    const { pathname } = locationBeforeTransitions || {};
    const { subject, id } = explodePath(pathname);
    const owner = { type: getEntityTypeId(subject), id };
    return { owner, ...dialogInitialValues(state) };
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
    const defaultOwner = { type: getEntityTypeId(subject), id };
    const { owner = defaultOwner, timeframeId, disclosureType, okrName, okrDetail,
      targetValue, unit, startDate, endDate, alignment = {} } = entry;
    const isOwnerCurrent = getOwnerTypeSubject(owner.type) === subject && owner.id === id;
    if (compareDates(startDate, endDate) > 0) {
      throw new SubmissionError({ _error: '終了日は開始日以降に設定してください' });
    }
    const { type: parentOwnerType, id: parentOwnerId } = parentOkr.owner || {};
    const okrType = type === 'Okr' || owner.type !== parentOwnerType ||
      owner.id !== parentOwnerId ? OKRType.OKR : OKRType.KR;
    const okr = {
      okrType,
      timeframeId,
      parentOkrId: parentOkr.id || alignment.id,
      ...mapOwnerOutbound(omit(owner, 'name')),
      disclosureType,
      okrName,
      okrDetail: okrDetail || '',
      targetValue: targetValue ? toNumber(targetValue) : 100,
      unit: unit || '%',
      startDate: toUtcDate(startDate),
      endDate: toUtcDate(endDate),
    };
    const dispatcher = okrType === OKRType.OKR ?
      partial(dispatchPostOkr, subject, isOwnerCurrent) : dispatchPostKR;
    return dispatcher(okr).then(({ error }) => !error && onClose());
  }

  render() {
    const { type, owner, ownerName, parentOkr, timeframeId, onClose, subject } = this.props;
    const okrForm = Form => (
      <Form
        title={type === 'Okr' ? '目標新規登録' : 'サブ目標新規登録'}
        submitButton="目標作成"
        onSubmit={this.submit.bind(this)}
        onClose={onClose}
      >
        <div className={styles.dialog}>
          {parentOkr && <EntitySubject entity={parentOkr.owner} heading="紐付け先目標" subject={name} />}
          <section>
            <label>担当者</label>
            {ownerName ? <label>{ownerName}</label> : ownerSearch}
            {type === 'Okr' && <label>目標の時間枠</label>}
            {type === 'Okr' && withSelectReduxField(TimeframesDropdown, 'timeframeId')}
          </section>
          <section>
            <label>公開範囲</label>
            {withSelectReduxField(DisclosureTypeOptions,
              'disclosureType',
              { entityType: (owner || {}).type || getEntityTypeId(subject),
                renderer: ({ value, label }) => (
                  <label key={value}>
                    <Field name="disclosureType" component="input" type="radio" value={value} />
                    {label}
                  </label>) },
            )}
          </section>
          <section>
            <Field component="textarea" name="okrName" placeholder="目標120字以内" maxLength={120} />
          </section>
          <section>
            <Field component="textarea" name="okrDetail" placeholder="詳細250字以内" maxLength={250} />
          </section>
          <section>
            <label>目標値</label>
            <div>
              <Field component="input" type="number" name="targetValue" />
              <small>※空欄の場合は100</small>
            </div>
            <label>単位</label>
            <div>
              <Field component="input" name="unit" placeholder="例)時間、回、件、枚" />
              <small>※途中で変更不可。空欄の場合は%</small>
            </div>
          </section>
          <section>
            <label>開始日</label>
            {withReduxField(DatePickerInput, 'startDate')}
            <label>期限日</label>
            {withReduxField(DatePickerInput, 'endDate')}
          </section>
          {type === 'Okr' && timeframeId && <section>
            <label>紐づけ先検索</label>
            {withItemisedReduxField(OKRSearch, 'alignment',
              { owner, timeframeId, disabled: !ownerName && isEmpty(owner) })}
          </section>}
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
