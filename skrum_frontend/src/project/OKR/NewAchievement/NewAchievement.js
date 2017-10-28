import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import DialogForm from '../../../dialogs/DialogForm';
import NumberInput from '../../../editors/NumberInput';
import InlineTextInput from '../../../editors/InlineTextInput';
import { withReduxForm, withNumberReduxField } from '../../../util/FormUtil';
import { postAchievement, putOKR } from '../../OKRDetails/action';
import { postAchievement as postBasicsAchievement, syncAchievement, syncOkr } from '../../OKR/action';
import styles from './NewAchievement.css';

const formName = 'newProgress';

const validate = ({ achievedValue, targetValue } = {}) => ({
  achievedValue: achievedValue < '0' && '開始日を入力してください',
  targetValue: targetValue < '0' && '終了日を入力してください',
});

class NewAchievement extends Component {

  static propTypes = {
    basicsOnly: PropTypes.bool,
    subject: PropTypes.string.isRequired,
    id: PropTypes.number.isRequired,
    achievedValue: PropTypes.number.isRequired,
    targetValue: PropTypes.number.isRequired,
    unit: PropTypes.string.isRequired,
    onClose: PropTypes.func.isRequired,
    dispatchPostAchievement: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
  };

  constructor(props) {
    super(props);
    const { id, achievedValue, targetValue } = props;
    this.form = withReduxForm(
      formProps => <DialogForm compact modeless {...formProps} />,
      `${formName}-${id}`,
      { initialValues: { achievedValue, targetValue }, validate },
    );
  }

  submit(entry) {
    const { id, onClose, dispatchPostAchievement } = this.props;
    this.setState({ isSubmitting: true });
    return dispatchPostAchievement(id, entry).then(({ error, payload }) => {
      this.setState({ isSubmitting: false }, () => !error && onClose());
      return { error, payload };
    });
  }

  render() {
    const { basicsOnly, id, unit, onClose, dispatchPutOKR } = this.props;
    const { isSubmitting = false } = this.state || {};
    const Form = this.form;
    return (
      <Form
        submitButton="登録"
        cancelButton={null}
        onSubmit={this.submit.bind(this)}
        isSubmitting={isSubmitting}
        onClose={onClose}
      >
        <div className={styles.dialog}>
          <div className={styles.progressConstituents}>
            <div className={styles.inputWithTitle}>
              <span className={styles.title}>達成値</span>
              {withNumberReduxField(NumberInput, 'achievedValue', { min: 0 })}
            </div>
            <span className={styles.label}>／</span>
            <div className={styles.inputWithTitle}>
              <span className={styles.title}>目標値</span>
              {withNumberReduxField(NumberInput, 'targetValue', { min: 0 })}
            </div>
            <InlineTextInput
              className={styles.unit}
              value={unit}
              required
              readonly={basicsOnly}
              onSubmit={value => dispatchPutOKR(id, { unit: value })}
            />
          </div>
          <Field component="textarea" name="post" placeholder="コメント（任意）" maxLength={2000} />
        </div>
      </Form>);
  }
}

const mapDispatchToProps = (dispatch, { subject, basicsOnly }) => {
  const dispatchPostAchievement = (id, entry) =>
    dispatch(
      (basicsOnly ? postBasicsAchievement(subject, id, entry) :
        postAchievement(id, entry, basicsOnly)))
      .then(({ payload, error }) => dispatch(syncAchievement(subject, { payload, error })));
  const dispatchPutOKR = (id, data) =>
    dispatch(putOKR(id, data))
      .then(result => dispatch(syncOkr(subject, result)));
  return { dispatchPostAchievement, dispatchPutOKR };
};

export default connect(
  null,
  mapDispatchToProps,
)(NewAchievement);
