import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import { toNumber } from 'lodash';
import DialogForm from '../../../dialogs/DialogForm';
import { withReduxForm } from '../../../util/FormUtil';
import { postAchievement } from '../../OKRDetails/action';
import styles from './NewAchievement.css';

const formName = 'newProgress';

const validate = ({ achievedValue, targetValue } = {}) => ({
  achievedValue: achievedValue < '0' && '開始日を入力してください',
  targetValue: targetValue < '0' && '終了日を入力してください',
});

class NewAchievement extends Component {

  static propTypes = {
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
      DialogForm,
      `${formName}-${id}`,
      { initialValues: { achievedValue, targetValue }, validate },
    );
  }

  submit({ achievedValue, targetValue, post }) {
    const { id, onClose, dispatchPostAchievement } = this.props;
    const entry = {
      achievedValue: toNumber(achievedValue),
      targetValue: toNumber(targetValue),
      post,
    };
    this.setState({ isSubmitting: true }, () =>
      dispatchPostAchievement(id, entry).then(({ error }) =>
        this.setState({ isSubmitting: false }, () => !error && onClose()),
      ),
    );
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
          <div className={styles.progressBox}>
            <div className={styles.inputWithTitle}>
              <span className={styles.title}>達成値</span>
              <Field component="input" type="number" name="achievedValue" />
            </div>
            <span className={styles.label}>／</span>
            <div className={styles.inputWithTitle}>
              <span className={styles.title}>目標値</span>
              <Field component="input" type="number" name="targetValue" />
            </div>
            <span className={styles.label}>{unit}</span>
          </div>
          <Field component="textarea" name="post" placeholder="コメント（任意）" maxLength={2000} />
        </div>
      </Form>);
  }
}

const mapDispatchToProps = (dispatch) => {
  const dispatchPostAchievement = (id, entry) =>
    dispatch(postAchievement(id, entry));
  return { dispatchPostAchievement };
};

export default connect(
  null,
  mapDispatchToProps,
)(NewAchievement);
