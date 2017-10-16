import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field, getFormValues, SubmissionError } from 'redux-form';
import { isEmpty, partial, find } from 'lodash';
import { objectivePropTypes } from '../../OKRDetails/propTypes';
import DialogForm from '../../../dialogs/DialogForm';
import OwnerSearch from '../../OwnerSearch/OwnerSearch';
import TimeframesDropdown from '../../../components/TimeframesDropdown';
import NumberInput from '../../../editors/NumberInput';
import DatePickerInput from '../../../editors/DatePickerInput';
import EntitySubject from '../../../components/EntitySubject';
import DisclosureTypeOptions, { getDisclosureTypeName } from '../../../components/DisclosureTypeOptions';
import OKRSearch from '../../OKRSearch/OKRSearch';
import { withLoadedReduxForm, withItemisedReduxField, withSelectReduxField, withNumberReduxField,
  withReduxField } from '../../../util/FormUtil';
import { entityPropTypes, getEntityTypeId, getEntityTypeSubject } from '../../../util/EntityUtil';
import { mapOwnerOutbound } from '../../../util/OwnerUtil';
import { explodePath } from '../../../util/RouteUtil';
import { isValidDate, compareDates, toUtcDate, formatDate } from '../../../util/DatetimeUtil';
import { OKRType } from '../../../util/OKRUtil';
import { postOkr, syncNewKR } from '../action';
import { postKR } from '../../OKRDetails/action';
import styles from './NewOKR.css';

const formName = 'NewOKR';

const validate = ({ owner, disclosureType, okrName, startDate, endDate } = {}) => ({
  owner: isEmpty(owner) && '担当者を入力してください',
  disclosureType: owner && !getDisclosureTypeName(owner.type, disclosureType) && '公開範囲を入力してください',
  okrName: !okrName && '目標名を入力してください',
  startDate: !isValidDate(startDate) && '開始日を入力してください',
  endDate: !isValidDate(endDate) && '期限日を入力してください',
});

const dialogInitialValues = (state) => {
  const { company = {} } = state.top.data || {};
  const { defaultDisclosureType } = company.policy || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { timeframeId } = explodePath(pathname);
  const { timeframes = [] } = state.top.data || {};
  const { startDate, endDate } = find(timeframes, { timeframeId }) || {};
  return {
    disclosureType: defaultDisclosureType,
    timeframeId,
    startDate: formatDate(startDate),
    endDate: formatDate(endDate) };
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

class NewOKR extends Component {

  static propTypes = {
    type: PropTypes.oneOf(['Okr', 'KR']).isRequired,
    onClose: PropTypes.func.isRequired,
    parentOkr: objectivePropTypes,
    ownerName: PropTypes.string,
    subject: PropTypes.string,
    id: PropTypes.number,
    owner: entityPropTypes,
    timeframeId: PropTypes.number,
    differingParentOkrOwner: PropTypes.bool,
    dispatchPostOkr: PropTypes.func,
    dispatchPostKR: PropTypes.func,
  };

  constructor(props) {
    super(props);
    const { differingParentOkrOwner, parentOkr, type } = props;
    // FIXME: moving ownerSearch to mapStateToProps causes infinite loop
    const ownerSearch = withItemisedReduxField(OwnerSearch, 'owner', {
      tabIndex: type === 'KR' ? 91 : 1,
      ...(differingParentOkrOwner && parentOkr ? { exclude: [parentOkr.owner] } : {}),
      ...(type === 'KR' && parentOkr ? { value: parentOkr.owner } : {}),
    });
    this.state = { ownerSearch };
  }

  submit(entry) {
    const { type, parentOkr = {}, onClose, subject, id,
      dispatchPostOkr, dispatchPostKR } = this.props;
    const defaultOwner = { type: getEntityTypeId(subject), id };
    const { owner = defaultOwner, timeframeId, disclosureType, okrName, okrDetail,
      targetValue, unit, startDate, endDate, alignment = {} } = entry;
    const isOwnerCurrent = getEntityTypeSubject(owner.type) === subject && owner.id === id;
    if (compareDates(startDate, endDate) > 0) {
      throw new SubmissionError({ _error: '期限日は開始日以降に設定してください' });
    }
    const { type: parentOwnerType, id: parentOwnerId } = parentOkr.owner || {};
    const okrType = type === 'Okr' || owner.type !== parentOwnerType ||
      owner.id !== parentOwnerId ? OKRType.OKR : OKRType.KR;
    const { type: ownerType, id: ownerId } = owner;
    const okr = {
      okrType,
      timeframeId,
      parentOkrId: parentOkr.id || alignment.id,
      ...mapOwnerOutbound({ type: ownerType, id: ownerId }),
      disclosureType,
      okrName,
      okrDetail: okrDetail || '',
      targetValue: targetValue || 100,
      unit: unit || '%',
      startDate: toUtcDate(startDate),
      endDate: toUtcDate(endDate),
    };
    const dispatcher = okrType === OKRType.OKR ?
      partial(dispatchPostOkr, subject, isOwnerCurrent) : partial(dispatchPostKR, subject);
    return dispatcher(okr).then(({ error, payload }) => {
      if (!error) onClose();
      return { error, payload };
    });
  }

  render() {
    const { subject, id, type, onClose, parentOkr, owner, ownerName, timeframeId } = this.props;
    const { ownerSearch } = this.state;
    const defaultOwner = { type: getEntityTypeId(subject), id };
    const startTabIndex = type === 'KR' || ownerName ? 90 : 0;
    const okrForm = Form => (
      <Form
        title={type === 'Okr' ? '目標新規登録' : 'サブ目標新規登録'}
        submitButton="目標作成"
        onSubmit={this.submit.bind(this)}
        onClose={onClose}
        lastTabIndex={80}
      >
        <div className={styles.dialog}>
          {parentOkr && <EntitySubject entity={parentOkr.owner} heading="紐付け先目標" subject={parentOkr.name} />}
          <section>
            <label>担当者*</label>
            {ownerName ? <label>{ownerName}</label> : ownerSearch}
            {!parentOkr && <label>目標の時間枠</label>}
            {!parentOkr && withSelectReduxField(TimeframesDropdown, 'timeframeId', { tabIndex: startTabIndex + 2 })}
          </section>
          <section>
            <label>公開範囲*</label>
            {withSelectReduxField(DisclosureTypeOptions,
              'disclosureType',
              { entityType: (owner || {}).type || getEntityTypeId(subject),
                renderer: ({ value, label }, index) => (
                  <label key={value}>
                    <Field name="disclosureType" component="input" type="radio" value={value} props={{ tabIndex: startTabIndex + index + 3 }} />
                    {label}
                  </label>) },
            )}
          </section>
          <section>
            <Field component="textarea" name="okrName" placeholder="目標120字以内（必須）" maxLength={120} props={{ tabIndex: 11 }} />
          </section>
          <section>
            <Field component="textarea" name="okrDetail" placeholder="詳細250字以内" maxLength={250} props={{ tabIndex: 12 }} />
          </section>
          <section>
            <label>目標値</label>
            <div>
              {withNumberReduxField(NumberInput, 'targetValue', { tabIndex: 13, min: 0 })}
              <small>※空欄の場合は100</small>
            </div>
            <label>単位</label>
            <div>
              <Field component="input" name="unit" placeholder="例)円、件、時間、回" props={{ tabIndex: 14 }} />
              <small>※空欄の場合は%</small>
            </div>
          </section>
          <section>
            <label>開始日*</label>
            {withReduxField(DatePickerInput, 'startDate', { tabIndex: 15 })}
            <label>期限日*</label>
            {withReduxField(DatePickerInput, 'endDate', { tabIndex: 16 })}
          </section>
          {!parentOkr && timeframeId && (
            <section>
              <label>紐付け先検索</label>
              <section>
                {withItemisedReduxField(
                  OKRSearch,
                  'alignment',
                  { tabIndex: 17,
                    owner: owner || defaultOwner,
                    timeframeId,
                    disabled: isEmpty(owner) && !ownerName },
                )}
                <small>※会社/部署/チーム/個人名または目標名で検索してください</small>
              </section>
            </section>)}
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
  const dispatchPostOkr = (subject, isOwnerCurrent, entry) =>
    dispatch(postOkr(subject, isOwnerCurrent, entry));
  const dispatchPostKR = (subject, entry) =>
    dispatch(postKR(entry)).then(result => dispatch(syncNewKR(subject, result)));
  return { dispatchPostOkr, dispatchPostKR };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NewOKR);
