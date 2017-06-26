import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import { toNumber } from 'lodash';
import DialogForm from '../../../dialogs/DialogForm';
import DatePickerInput from '../../../components/DatePickerInput';
import OwnerSearch from '../../OwnerSearch/OwnerSearch';
import OKRSearch from '../OKRSearch/OKRSearch';
import { withLoadedReduxForm, withItemisedReduxField, withReduxField } from '../../../util/FormUtil';
import { getOwnerTypeSubject } from '../../../util/OwnerUtil';
import { explodePath } from '../../../util/RouteUtil';
import { convertToUtc } from '../../../util/DatetimeUtil';
import { postOKR } from './action';
import styles from './NewOKR.css';

const OKRDialogForm = withLoadedReduxForm(
  DialogForm,
  'newOKR',
  (state) => {
    const { company = {} } = state.top.data || {};
    const { defaultDisclosureType } = company.policy || {};
    return { disclosureType: defaultDisclosureType };
  });

class NewOKR extends Component {

  static propTypes = {
    type: PropTypes.oneOf(['Okr', 'KR']).isRequired,
    onClose: PropTypes.func.isRequired,
    timeframeId: PropTypes.number,
    dispatchPostOKR: PropTypes.func,
  };

  onSubmit(entry) {
    const { type, timeframeId, dispatchPostOKR } = this.props;
    const { owner = {}, startDate, endDate, disclosureType, okrName, okrDetail,
      targetValue, unit, alignment = {} } = entry;
    const ownerSubject = `owner${getOwnerTypeSubject(owner.type)}`;
    const okr = {
      okrType: type === 'Okr' ? '1' : '2',
      timeframeId: toNumber(timeframeId),
      parentOkrId: alignment.id, // todo for KR
      ownerType: owner.type,
      [`${ownerSubject}Id`]: owner.id,
      startDate: convertToUtc(startDate),
      endDate: convertToUtc(endDate),
      disclosureType,
      okrName,
      okrDetail,
      targetValue: toNumber(targetValue),
      unit,
    };
    dispatchPostOKR(okr);
  }

  render() {
    const { type, onClose } = this.props;
    const disclosureTypes = [
      { value: '1', label: '全体' },
      { value: '2', label: 'グループ' },
      { value: '3', label: '管理者' },
      { value: '4', label: 'グループ管理者' },
      { value: '5', label: '本人のみ' },
    ];
    return (
      <OKRDialogForm
        title={type === 'Okr' ? '目標新規登録' : 'サブ目標新規登録'}
        submitButton="OKR追加"
        onSubmit={this.onSubmit.bind(this)}
        onClose={onClose}
      >
        <div className={styles.dialog}>
          <div className={styles.ownerDateRange}>
            <div className={styles.ownerBox}>
              <span className={styles.label}>所有者</span>
              {withItemisedReduxField(OwnerSearch, 'owner')}
            </div>
            <div className={styles.dateRange}>
              <span className={styles.label}>開始日</span>
              {withReduxField(DatePickerInput, 'startDate')}
              <span className={styles.label}>期限日</span>
              {withReduxField(DatePickerInput, 'endDate')}
            </div>
          </div>
          <div className={styles.disclosureType}>
            <span className={styles.label}>公開</span>
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
            <span className={styles.label}>目標数字</span>
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
          <div className={styles.alignmentBox}>
            <span className={styles.label}>紐づけ先検索</span>
            {withItemisedReduxField(OKRSearch, 'alignment')}
          </div>
        </div>
      </OKRDialogForm>
    );
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { timeframeId } = explodePath(pathname);
  return { timeframeId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostOKR = entry => dispatch(postOKR(entry));
  return { dispatchPostOKR };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NewOKR);
