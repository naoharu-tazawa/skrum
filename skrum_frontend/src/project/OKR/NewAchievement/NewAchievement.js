import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import DialogForm from '../../../dialogs/DialogForm';
import NumberInput from '../../../editors/NumberInput';
import { withReduxForm, withNumberReduxField } from '../../../util/FormUtil';
import { postAchievement } from '../../OKRDetails/action';
import { syncOkr } from '../../OKR/action';
import styles from './NewAchievement.css';

const formName = 'newProgress';

const validate = ({ achievedValue, targetValue } = {}) => ({
  achievedValue: achievedValue < '0' && '開始日を入力してください',
  targetValue: targetValue < '0' && '終了日を入力してください',
});

class NewAchievement extends Component {

  static propTypes = {
    subject: PropTypes.string.isRequired,
    id: PropTypes.number.isRequired,
    achievedValue: PropTypes.number.isRequired,
    targetValue: PropTypes.number.isRequired,
    unit: PropTypes.string.isRequired,
    onClose: PropTypes.func.isRequired,
    dispatchPostAchievement: PropTypes.func,
  };

  constructor(props) {
    super(props);
    const { id, achievedValue, targetValue } = props;
    this.form = withReduxForm(
      formProps => <DialogForm compact {...formProps} />,
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
    const { unit, onClose } = this.props;
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
            <span className={styles.label}>{unit}</span>
          </div>
          <Field component="textarea" name="post" placeholder="コメント（任意）" maxLength={2000} />
        </div>
      </Form>);
  }
}

const mapDispatchToProps = (dispatch, { subject }) => {
  const dispatchPostAchievement = (id, entry) =>
    dispatch(postAchievement(id, entry))
      .then(({ payload: { data: { data: { parentOkr = {} } = {} } = {}, message }, error }) =>
        dispatch(syncOkr(subject, { payload: { data: parentOkr, message }, error })));
  return { dispatchPostAchievement };
};

export default connect(
  null,
  mapDispatchToProps,
)(NewAchievement);
