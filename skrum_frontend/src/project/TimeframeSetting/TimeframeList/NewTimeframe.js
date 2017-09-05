import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field, SubmissionError } from 'redux-form';
import DialogForm from '../../../dialogs/DialogForm';
import DatePickerInput from '../../../components/DatePickerInput';
import { withLoadedReduxForm, withReduxField } from '../../../util/FormUtil';
import { isValidDate, compareDates, toUtcDate } from '../../../util/DatetimeUtil';
import { postTimeframe } from '../action';

const validate = ({ name, startDate, endDate } = {}) => ({
  name: !name && '目標期間名を入力してください',
  startDate: !isValidDate(startDate) && '開始日を入力してください',
  endDate: !isValidDate(endDate) && '終了日を入力してください',
});

const NewTimeframeForm = withLoadedReduxForm(
  DialogForm,
  'NewTimeframe',
  null,
  { validate });

class NewTimeframe extends Component {

  static propTypes = {
    onClose: PropTypes.func.isRequired,
    dispatchPostTimeframe: PropTypes.func.isRequired,
  };

  submit(entry) {
    const { onClose, dispatchPostTimeframe } = this.props;
    const { name, startDate, endDate } = entry;
    if (compareDates(startDate, endDate) > 0) {
      throw new SubmissionError({ _error: '終了日は開始日以降に設定してください' });
    }
    const timeframe = {
      timeframeName: name,
      startDate: toUtcDate(startDate),
      endDate: toUtcDate(endDate),
    };
    return dispatchPostTimeframe(timeframe).then(({ error }) => !error && onClose());
  }

  render() {
    const { onClose } = this.props;
    return (
      <NewTimeframeForm
        title="目標期間の作成"
        submitButton="目標期間作成"
        onSubmit={this.submit.bind(this)}
        onClose={onClose}
      >
        <section>
          <label>目標期間名</label>
          <Field component="input" name="name" maxLength={80} props={{ style: { flexGrow: 1 } }} />
        </section>
        <section>
          <label>開始日</label>
          {withReduxField(DatePickerInput, 'startDate')}
          <label>終了日</label>
          {withReduxField(DatePickerInput, 'endDate')}
        </section>
      </NewTimeframeForm>);
  }
}

const mapDispatchToProps = (dispatch) => {
  const dispatchPostTimeframe = entry =>
    dispatch(postTimeframe(entry));
  return { dispatchPostTimeframe };
};

export default connect(
  null,
  mapDispatchToProps,
)(NewTimeframe);
