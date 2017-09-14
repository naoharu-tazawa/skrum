import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { reset } from 'redux-form';
import { toastr } from 'react-redux-toastr';
import { errorType } from '../../../util/PropUtil';
import { postInvite } from '../action';
import InvitationForm, { formName } from './InvitationForm';
import styles from './InvitationContainer.css';

class InvitationContainer extends Component {

  static propTypes = {
    isPostingInvite: PropTypes.bool.isRequired,
    dispatchPostInvite: PropTypes.func.isRequired,
    dispatchResetForm: PropTypes.func.isRequired,
    error: errorType,
  };

  constructor(props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleSubmit({ emailAddress, roleAssignmentId }) {
    const { dispatchPostInvite, dispatchResetForm } = this.props;
    dispatchPostInvite(emailAddress, parseInt(roleAssignmentId, 10))
      .then(({ error, payload }) => {
        if (error) {
          toastr.error('メール送信に失敗しました');
        } else {
          toastr.info('招待メールを送信しました');
          dispatchResetForm();
        }
        return { error, payload };
      });
  }

  render() {
    const { isPostingInvite, error } = this.props;
    return (
      <section>
        <div className={styles.title}>ユーザ招待</div>
        <InvitationForm
          isPostingInvite={isPostingInvite}
          error={error}
          onSubmit={this.handleSubmit}
        />
      </section>);
  }
}

const mapStateToProps = (state) => {
  const { isPostingInvite, error } = state.userSetting || {};
  return { isPostingInvite, error };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostInvite = (emailAddress, roleAssignmentId) =>
    dispatch(postInvite(emailAddress, roleAssignmentId));
  const dispatchResetForm = () => dispatch(reset(formName));
  return {
    dispatchPostInvite,
    dispatchResetForm,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(InvitationContainer);
