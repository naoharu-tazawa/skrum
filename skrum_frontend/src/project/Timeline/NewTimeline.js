import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Field, propTypes } from 'redux-form';
import EntityLink, { EntityType } from '../../components/EntityLink';
import DisclosureTypeOptions from '../../components/DisclosureTypeOptions';
import { errorType } from '../../util/PropUtil';
import { withLoadedReduxForm, withSelectReduxField } from '../../util/FormUtil';
import { getEntityTypeId } from '../../util/EntityUtil';
import { explodePath } from '../../util/RouteUtil';
import styles from './NewTimeline.css';

class NewTimeline extends Component {

  static propTypes = {
    ...propTypes,
    currentUserId: PropTypes.number,
    subject: PropTypes.string,
    isPosting: PropTypes.bool,
    error: errorType,
    handleSubmit: PropTypes.func,
  };

  renderButton = isPosting =>
    (isPosting ?
      <div className={styles.busy_btn} /> :
      <button type="submit" className={styles.btn}>投稿する</button>);

  render() {
    const { currentUserId, subject, isPosting, handleSubmit } = this.props;
    return (
      <form onSubmit={handleSubmit}>
        <h1 className={styles.ttl_section}>新規投稿作成</h1>
        <div className={styles.cont_box}>
          <EntityLink
            componentClassName={styles.current_user}
            entity={{ id: currentUserId, type: EntityType.USER }}
            local
          />
          <div className={styles.text_area}>
            <Field
              name="post"
              component="textarea"
              placeholder="仕事の状況はどうですか？"
            />
          </div>
          <section>
            <label>公開範囲</label>
            {withSelectReduxField(DisclosureTypeOptions,
              'disclosureType',
              { entityType: getEntityTypeId(subject) },
            )}
            <div className={styles.btn}>{this.renderButton(isPosting)}</div>
          </section>
        </div>
      </form>);
  }
}

const dialogInitialValues = (state) => {
  const { company = {} } = state.top.data || {};
  const { defaultDisclosureType } = company.policy || {};
  return { disclosureType: defaultDisclosureType };
};

const mapStateToProps = (state) => {
  const { userId: currentUserId } = state.auth || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject } = explodePath(pathname);
  return { currentUserId, subject };
};

export const formName = 'NewTimeline';

export default withLoadedReduxForm(
  connect(mapStateToProps)(NewTimeline),
  formName,
  dialogInitialValues,
);
