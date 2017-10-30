import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import { isEmpty } from 'lodash';
import { objectiveReferencePropTypes } from '../OKRList/propTypes';
import DialogForm from '../../../dialogs/DialogForm';
import EntitySubject from '../../../components/EntitySubject';
import DatePickerInput from '../../../editors/DatePickerInput';
import UserSearch from '../../UserSearch/UserSearch';
import Options from '../../../components/Options';
import { postOneOnOne } from '../action';
// import { EntityType } from '../../../util/EntityUtil';
import { withReduxForm, withReduxField, withSelectReduxField, withItemisedReduxField } from '../../../util/FormUtil';
import { isValidDate, getDate, formatDate, toUtcDate } from '../../../util/DatetimeUtil';
import styles from './NewOneOnOne.css';

const formName = 'newOneOnOne';

const validate =
  ({ oneOnOneType, reportDate, dueDate, interviewDate, interviewee, to, body } = {}) => ({
    reportDate: !isValidDate(reportDate) && '日付を入力してください',
    dueDate: !isValidDate(dueDate) && '回答期限を入力してください',
    interviewDate: !isValidDate(interviewDate) && '面談日を入力してください',
    interviewee: isEmpty(interviewee) && '面談相手を入力してください',
    to: isEmpty(to) && 'ＴＯを入力してください',
    body: oneOnOneType !== '3' && !body && 'コメントを入力してください',
  });

class NewOneOnOne extends Component {

  static propTypes = {
    currentUserId: PropTypes.number.isRequired,
    userId: PropTypes.number.isRequired,
    okr: objectiveReferencePropTypes,
    onClose: PropTypes.func.isRequired,
    dispatchPostOneOnOne: PropTypes.func.isRequired,
  };

  constructor(props) {
    super(props);
    const { currentUserId, userId, okr } = props;
    const isSelf = currentUserId === userId;
    const oneOnOneType = (isSelf && (!okr ? '1' : '2')) || '3';
    const reportDate = formatDate(getDate());
    const dueDate = formatDate(getDate().add(3, 'd'));
    const interviewee = {}; // type: EntityType.USER, id: 3, name: '田中 二郎' }; // TODO call API
    const interviewDate = formatDate(getDate());
    const to = {}; // { type: EntityType.USER, id: 2, name: '田中 一郎' }; // TODO call API
    const { id: okrId } = okr || {};
    const initialValues = {
      ...{ oneOnOneType, reportDate, dueDate, feedbackType: '1' },
      ...{ interviewDate, interviewee, to, okrId } };
    this.form = withReduxForm(
      formProps => <DialogForm plain compact modeless className={styles.form} {...formProps} />,
      `${formName}-${userId}-${okrId}`,
      { initialValues, validate },
    );
  }

  submit(entry) {
    const { currentUserId, onClose, dispatchPostOneOnOne } = this.props;
    this.setState({ isSubmitting: true });
    const { oneOnOneType, reportDate, dueDate, feedbackType, interviewDate, interviewee,
      okrId, to, body } = entry;
    const report = {
      oneOnOneType,
      ...(oneOnOneType === '1' || oneOnOneType === '2') && { reportDate: toUtcDate(reportDate) },
      ...oneOnOneType === '3' && { dueDate: toUtcDate(dueDate) },
      ...oneOnOneType === '4' && { feedbackType },
      ...oneOnOneType === '5' && { intervieweeUserId: interviewee.id },
      ...oneOnOneType === '5' && { interviewDate: toUtcDate(interviewDate) },
      to: [{ userId: to.id }], // FIXME
      okrId,
      body: body || '',
    };
    return dispatchPostOneOnOne(currentUserId, report).then(({ error, payload }) => {
      this.setState({ isSubmitting: false }, () => !error && onClose());
      return { error, payload };
    });
  }

  render() {
    const { currentUserId, userId, okr, onClose } = this.props;
    const isSelf = currentUserId === userId;
    const { activeTab = isSelf ? 'report' : 'hearing', isSubmitting = false } = this.state || {};
    const selectableTabStyle = tab =>
      `${styles.tab} ${styles.selectable} ${activeTab === tab ? styles.active : ''} ${okr && tab === 'interview' && styles.disabled}`;
    const tabs = {
      hearing: 'ヒアリング',
      feedback: 'フィードバック',
      interview: '面談メモ',
    };
    const Form = this.form;
    return (
      <Form
        footerContent={activeTab === 'hearing' && (
          <span>※内容未記入の場合、<p />&emsp;自動メッセージが送信されます。</span>)}
        submitButton="送信"
        cancelButton={null}
        onSubmit={this.submit.bind(this)}
        isSubmitting={isSubmitting}
        onClose={onClose}
        activeTab={activeTab}
      >
        <div className={styles.dialog}>
          <nav className={styles.nav}>
            <button
              type="button"
              className={styles.close}
              onClick={onClose}
            >
              <img src="/img/delete.png" alt="" />
            </button>
          </nav>
          <header>
            {isSelf && <span className={styles.tab}>{!okr ? '日報' : '進捗報告'}</span>}
            {!isSelf && withSelectReduxField(Options,
              'oneOnOneType',
              { map: tabs,
                renderer: ({ value: tab, label }, index) => (
                  <label key={tab} className={selectableTabStyle(tab)}>
                    <Field
                      name="oneOnOneType"
                      component="input"
                      type="radio"
                      value={index + 3}
                      props={{
                        disabled: okr && tab === 'interview',
                        onClick: () => this.setState({ activeTab: tab }),
                      }}
                    />
                    {label}
                  </label>),
              },
            )}
          </header>
          {okr && (
            <EntitySubject
              className={styles.okr}
              heading="対象目標"
              entity={okr.owner}
              subject={okr.name}
            />)}
          {activeTab === 'report' && (
            <section>
              <label>日付</label>
              {withReduxField(DatePickerInput, 'reportDate')}
            </section>)}
          {activeTab === 'hearing' && (
            <section>
              <label>回答期限</label>
              {withReduxField(DatePickerInput, 'dueDate')}
            </section>)}
          {activeTab === 'feedback' && (
            <section>
              {withSelectReduxField(Options,
                'feedbackType',
                { map: { 1: '課題共有', 2: 'アドバイス', 3: 'グッジョブ', 4: '感謝' },
                  renderer: ({ value, label }) => (
                    <label key={value}>
                      <Field name="feedbackType" component="input" type="radio" value={value} />
                      {label}
                    </label>),
                },
              )}
            </section>)}
          {activeTab === 'interview' && (
            <section>
              <label>面談相手</label>
              {withItemisedReduxField(UserSearch, 'interviewee')}
            </section>)}
          {activeTab === 'interview' && (
            <section>
              <label>面談日</label>
              {withReduxField(DatePickerInput, 'interviewDate')}
            </section>)}
          <section>
            <label>ＴＯ</label>
            {withItemisedReduxField(UserSearch, 'to')}
          </section>
          <Field
            component="textarea"
            name="body"
            placeholder={activeTab === 'hearing' ? 'こちらの目標進捗はどうなっていますか？' : 'コメントを入力'}
            maxLength={50000}
          />
        </div>
      </Form>);
  }
}

const mapStateToProps = (state) => {
  const { userId: currentUserId } = state.auth || {};
  return { currentUserId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostOneOnOne = (userId, entry) =>
    dispatch(postOneOnOne(userId, entry));
  return { dispatchPostOneOnOne };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NewOneOnOne);
