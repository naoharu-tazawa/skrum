import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field } from 'redux-form';
import { isEmpty, keys, isArray, fromPairs, ceil } from 'lodash';
import { oneOnOneTypes, feedbackTypes } from '../propTypes';
import { oneOnOneSettingsPropTypes } from './propTypes';
import { objectiveReferencePropTypes } from '../../OKR/OKRList/propTypes';
import DialogForm from '../../../dialogs/DialogForm';
import DatePickerInput from '../../../editors/DatePickerInput';
import OKRSearch from '../../OKRSearch/OKRSearch';
import UserSearch, { MultiUserSearch } from '../../UserSearch/UserSearch';
import Options from '../../../components/Options';
import { postOneOnOneNote } from '../action';
import { syncOneOnOne } from '../../../navigation/action';
import { EntityType, entityPropTypes } from '../../../util/EntityUtil';
import { withReduxForm, withReduxField, withSelectReduxField, withItemisedReduxField } from '../../../util/FormUtil';
import { isValidDate, getDate, formatDate, toUtcDate } from '../../../util/DatetimeUtil';
import styles from './NewOneOnOneNote.css';

const formName = 'newOneOnOne';

const validate =
  ({ oneOnOneType, okr, reportDate, dueDate, interviewDate, interviewee, [`to${oneOnOneType}`]: to = [], body } = {}) => ({
    okr: oneOnOneType === '2' && isEmpty(okr) && '対象目標を入力してください',
    reportDate: !isValidDate(reportDate) && '日付を入力してください',
    dueDate: !isValidDate(dueDate) && '回答期限を入力してください',
    interviewDate: !isValidDate(interviewDate) && '面談日を入力してください',
    interviewee: isEmpty(interviewee) && '面談相手を入力してください',
    [`to${oneOnOneType}`]: oneOnOneType !== '2' && oneOnOneType !== '5' && !to.length && 'ＴＯを入力してください',
    body: oneOnOneType !== '3' && !body && 'コメントを入力してください',
  });

class NewOneOnOneNote extends Component {

  static propTypes = {
    types: PropTypes.oneOfType([
      PropTypes.oneOf(keys(oneOnOneTypes)),
      PropTypes.arrayOf(PropTypes.oneOf(keys(oneOnOneTypes))),
    ]).isRequired,
    oneOnOne: oneOnOneSettingsPropTypes.isRequired,
    currentUserId: PropTypes.number.isRequired,
    userId: PropTypes.number.isRequired,
    okr: PropTypes.oneOfType([objectiveReferencePropTypes, PropTypes.shape({})]),
    feedback: entityPropTypes,
    onClose: PropTypes.func.isRequired,
    dispatchPostOneOnOneNote: PropTypes.func.isRequired,
  };

  constructor(props) {
    super(props);
    const { oneOnOne, types, userId, okr, feedback } = props;
    const firstType = isArray(types) ? types[0] : types;
    const oneOnOneType = `${keys(oneOnOneTypes).indexOf(firstType) + 1}`;
    const reportDate = formatDate(getDate());
    const dueDate = formatDate(getDate().add(3, 'd'));
    const interviewee = feedback || {};
    const interviewDate = formatDate(getDate());
    const toUserEntity = ({ userId: id, name }) => (id ? { type: EntityType.USER, id, name } : {});
    const oneOnOneTos = oneOnOne.reduce((tos, { type, to }) =>
      ({ ...tos, [`to${type}`]: (feedback && [feedback]) || to.map(toUserEntity) }), {});
    const { id: okrId } = okr || {};
    const initialValues = {
      ...{ oneOnOneType, okr, reportDate, dueDate, feedbackType: '1' },
      ...{ interviewDate, interviewee, ...oneOnOneTos } };
    this.form = withReduxForm(
      formProps =>
        <DialogForm modeless plain compact fullHeight className={styles.form} {...formProps} />,
      `${formName}-${userId}-${okrId}`,
      { initialValues, validate },
    );
  }

  submit({ oneOnOneType, reportDate, dueDate, feedbackType, interviewDate, interviewee,
    okr, [`to${oneOnOneType}`]: to, body = '' }) {
    const { currentUserId, onClose, dispatchPostOneOnOneNote } = this.props;
    this.setState({ isSubmitting: true });
    const entry = {
      oneOnOneType,
      ...(oneOnOneType === '1' || oneOnOneType === '2') && { reportDate: toUtcDate(reportDate) },
      ...oneOnOneType === '3' && { dueDate: toUtcDate(dueDate) },
      ...oneOnOneType === '4' && { feedbackType },
      ...oneOnOneType === '5' && { intervieweeUserId: interviewee.id },
      ...oneOnOneType === '5' && { interviewDate: toUtcDate(interviewDate) },
      ...to && { to: to.map(({ id }) => ({ userId: id })) },
      ...!isEmpty(okr) && { okrId: okr.id },
      body,
    };
    return dispatchPostOneOnOneNote(currentUserId, entry, { oneOnOneType, to })
      .then(({ error, payload }) => {
        this.setState({ isSubmitting: false }, () => !error && onClose());
        return { error, payload };
      });
  }

  render() {
    const { types, okr, onClose } = this.props;
    const firstType = isArray(types) ? types[0] : types;
    const {
      activeTab = firstType,
      oneOnOneType = `${keys(oneOnOneTypes).indexOf(firstType) + 1}`,
      isSubmitting = false,
    } = this.state || {};
    const selectableTabStyle = tab =>
      `${styles.tab} ${activeTab === tab ? styles.active : styles.selectable} ${okr && tab === 'interviewNote' && styles.disabled}`;
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
            <a
              className={styles.close}
              tabIndex={0}
              onClick={onClose}
            >
              <img src="/img/delete_grey.png" alt="Close" />
            </a>
          </nav>
          <header>
            {!isArray(types) && <span className={styles.tab}>{oneOnOneTypes[firstType]}</span>}
            {isArray(types) && withSelectReduxField(Options,
              'oneOnOneType',
              { map: fromPairs(types.map(type => [type, oneOnOneTypes[type]])),
                renderer: ({ value: tab, label }) => (
                  <label key={tab} className={selectableTabStyle(tab)} style={{ width: `${ceil(100 / types.length)}%` }}>
                    <Field
                      name="oneOnOneType"
                      component="input"
                      type="radio"
                      value={`${keys(oneOnOneTypes).indexOf(tab) + 1}`}
                      props={{
                        disabled: okr && tab === 'interviewNote',
                        onClick: () => this.setState({
                          activeTab: tab,
                          oneOnOneType: `${keys(oneOnOneTypes).indexOf(tab) + 1}`,
                        }),
                      }}
                    />
                    {label}
                  </label>),
                className: styles.options,
              },
            )}
          </header>
          {okr && activeTab !== 'dailyReport' && activeTab !== 'interviewNote' && (
            <section>
              <label>対象目標</label>
              {withItemisedReduxField(OKRSearch, 'okr', { loadBasicsOKRs: EntityType.USER })}
            </section>)}
          {activeTab === 'dailyReport' && (
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
                { map: feedbackTypes,
                  renderer: ({ value, label }) => (
                    <label key={value}>
                      <Field name="feedbackType" component="input" type="radio" value={value} />
                      {label}
                    </label>),
                },
              )}
            </section>)}
          {activeTab === 'interviewNote' && (
            <section>
              <label>面談相手</label>
              {withItemisedReduxField(UserSearch, 'interviewee')}
            </section>)}
          {activeTab === 'interviewNote' && (
            <section>
              <label>面談日</label>
              {withReduxField(DatePickerInput, 'interviewDate')}
            </section>)}
          <section>
            <label>ＴＯ</label>
            {withItemisedReduxField(MultiUserSearch, `to${oneOnOneType}`)}
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
