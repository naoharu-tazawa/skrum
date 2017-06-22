import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { toastr } from 'react-redux-toastr';
import { errorType } from '../../../util/PropUtil';
import { rolesPropTypes } from './propTypes';
import { postInvite } from '../action';
import InvitationForm from './InvitationForm';
import styles from './InvitationContainer.css';

class InvitationContainer extends Component {

  static propTypes = {
    items: rolesPropTypes,
    isPosting: PropTypes.bool,
    dispatchPostInvite: PropTypes.func,
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
    const { items, isPosting, error } = this.props;
    return (
      <section>
        <div className={styles.title}>ユーザ招待</div>
        <InvitationForm
          items={items}
          isPosting={isPosting}
          error={error}
          onSubmit={this.handleSubmit}
        />
      </section>);
  }
}

const mapStateToProps = (state) => {
  const { roles = [], isPosting = false, error } = state.userSetting || {};
  const items = roles.map((role) => {
    const { roleAssignmentId, roleName } = role;
    return {
      id: roleAssignmentId,
      name: roleName,
    };
  });
  return { items, isPosting, error };
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
