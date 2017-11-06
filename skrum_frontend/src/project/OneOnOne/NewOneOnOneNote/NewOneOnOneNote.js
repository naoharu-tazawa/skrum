import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import { isEmpty, head } from 'lodash';
import { oneOnOneSettingsPropTypes } from './propTypes';
import { objectiveReferencePropTypes } from '../../OKR/OKRList/propTypes';
import DialogForm from '../../../dialogs/DialogForm';
import DatePickerInput from '../../../editors/DatePickerInput';
import OKRSearch from '../../OKRSearch/OKRSearch';
import UserSearch from '../../UserSearch/UserSearch';
import Options from '../../../components/Options';
import { postOneOnOneNote } from '../action';
import { syncOneOnOne } from '../../../navigation/action';
import { EntityType } from '../../../util/EntityUtil';
import { withReduxForm, withReduxField, withSelectReduxField, withItemisedReduxField } from '../../../util/FormUtil';
import { isValidDate, getDate, formatDate, toUtcDate } from '../../../util/DatetimeUtil';
import styles from './NewOneOnOneNote.css';

const formName = 'newOneOnOne';

const validate =
  ({ oneOnOneType, reportDate, dueDate, interviewDate, interviewee, to, body } = {}) => ({
    reportDate: !isValidDate(reportDate) && '日付を入力してください',
    dueDate: !isValidDate(dueDate) && '回答期限を入力してください',
    interviewDate: !isValidDate(interviewDate) && '面談日を入力してください',
    interviewee: isEmpty(interviewee) && '面談相手を入力してください',
    to: oneOnOneType !== '2' && oneOnOneType !== '5' && isEmpty(to) && 'ＴＯを入力してください',
    body: oneOnOneType !== '3' && !body && 'コメントを入力してください',
  });

class NewOneOnOneNote extends Component {

  static propTypes = {
    oneOnOne: oneOnOneSettingsPropTypes.isRequired,
    currentUserId: PropTypes.number.isRequired,
    userId: PropTypes.number.isRequired,
    okr: objectiveReferencePropTypes,
    onClose: PropTypes.func.isRequired,
    dispatchPostOneOnOneNote: PropTypes.func.isRequired,
  };

  constructor(props) {
    super(props);
    const { oneOnOne, currentUserId, userId, okr } = props;
    const isSelf = currentUserId === userId;
    const oneOnOneType = (isSelf && (!okr ? '1' : '2')) || '3';
    const reportDate = formatDate(getDate());
    const dueDate = formatDate(getDate().add(3, 'd'));
    const interviewee = {}; // type: EntityType.USER, id: 3, name: '田中 二郎' }; // TODO call API
    const interviewDate = formatDate(getDate());
    const toUserEntity = ({ userId: id, name }) => (id ? { type: EntityType.USER, id, name } : {});
    const oneOnOneTos = oneOnOne.reduce((tos, { type, to }) =>
      ({ ...tos, [`to${type}`]: toUserEntity(head(to) || {}) }), {});
    const { id: okrId } = okr || {};
    const initialValues = {
      ...{ oneOnOneType, okr, reportDate, dueDate, feedbackType: '1' },
      ...{ interviewDate, interviewee, ...oneOnOneTos } };
    this.form = withReduxForm(
      formProps => <DialogForm plain compact modeless className={styles.form} {...formProps} />,
      `${formName}-${userId}-${okrId}`,
      { initialValues, validate },
    );
  }

  submit(entry) {
    const { currentUserId, onClose, dispatchPostOneOnOneNote } = this.props;
    this.setState({ isSubmitting: true });
    const { oneOnOneType, reportDate, dueDate, feedbackType, interviewDate, interviewee,
      okr, [`to${oneOnOneType}`]: to, body = '' } = entry;
    const report = {
      oneOnOneType,
      ...(oneOnOneType === '1' || oneOnOneType === '2') && { reportDate: toUtcDate(reportDate) },
      ...oneOnOneType === '3' && { dueDate: toUtcDate(dueDate) },
      ...oneOnOneType === '4' && { feedbackType },
      ...oneOnOneType === '5' && { intervieweeUserId: interviewee.id },
      ...oneOnOneType === '5' && { interviewDate: toUtcDate(interviewDate) },
      ...to.id && { to: [{ userId: to.id }] }, // FIXME
      ...okr && { okrId: okr.id },
      body,
    };
    return dispatchPostOneOnOneNote(currentUserId, report, { oneOnOneType, to: [to] }) // FIXME
      .then(({ error, payload }) => {
        this.setState({ isSubmitting: false }, () => !error && onClose());
        return { error, payload };
      });
  }

  render() {
    const { currentUserId, userId, okr, onClose } = this.props;
    const isSelf = currentUserId === userId;
    const { activeTab = isSelf ? 'report' : 'hearing',
      oneOnOneType = (isSelf && (!okr ? '1' : '2')) || '3', isSubmitting = false } = this.state || {};
    const selectableTabStyle = tab =>
      `${styles.tab} ${styles.selectable} ${activeTab === tab ? styles.active : ''} ${okr && tab === 'interview' && styles.disabled}`;
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
      >
        <div className={styles.dialog}>
          <nav className={styles.nav}>
            <button
              type="button"
              className={styles.close}
              onClick={onClose}
            >
              <img src="/img/delete_grey.png" alt="" />
            </button>
          </nav>
          <header>
            {isSelf && <span className={styles.tab}>{!okr ? '日報' : '進捗報告'}</span>}
            {!isSelf && withSelectReduxField(Options,
              'oneOnOneType',
              { map: { hearing: 'ヒアリング', feedback: 'フィードバック', interview: '面談メモ' },
                renderer: ({ value: tab, label }, index) => (
                  <label key={tab} className={selectableTabStyle(tab)}>
                    <Field
                      name="oneOnOneType"
                      component="input"
                      type="radio"
                      value={index + 3}
                      props={{
                        disabled: okr && tab === 'interview',
                        onClick: () => this.setState({ activeTab: tab, oneOnOneType: `${index + 3}` }),
                      }}
                    />
                    {label}
                  </label>),
              },
            )}
          </header>
          {okr && (
            <section>
              <label>対象目標</label>
              {withItemisedReduxField(OKRSearch, 'okr', { loadBasicsOKRs: EntityType.USER })}
            </section>)}
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
                { map: { 1: 'ありがとう', 2: 'アドバイス', 3: 'グッジョブ', 4: '課題点' },
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
            {withItemisedReduxField(UserSearch, `to${oneOnOneType}`)}
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
  const { data: topData } = state.top || {};
  const { oneOnOne = [] } = topData || {};
  return { oneOnOne, currentUserId };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostOneOnOneNote = (userId, entry, sync) =>
    dispatch(postOneOnOneNote(userId, entry))
      .then(result => dispatch(syncOneOnOne({ ...result, payload: { data: sync } })));
  return { dispatchPostOneOnOneNote };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(NewOneOnOneNote);
