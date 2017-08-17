import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { toastr } from 'react-redux-toastr';
import { errorType } from '../../../util/PropUtil';
import { postInvite } from '../action';
import InvitationForm from './InvitationForm';
import styles from './InvitationContainer.css';

class InvitationContainer extends Component {

  static propTypes = {
    isPostingInvite: PropTypes.bool.isRequired,
    dispatchPostInvite: PropTypes.func.isRequired,
    error: errorType,
  };

  constructor(props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleSubmit(data) {
    this.props.dispatchPostInvite(
      data.emailAddress,
      parseInt(data.roleAssignmentId, 10),
    ).then((ret) => {
      if ('error' in ret) {
        toastr.error('メール送信に失敗しました');
      } else {
        toastr.info('招待メールを送信しました');
      }
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
  return { dispatchPostInvite };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(InvitationContainer);
