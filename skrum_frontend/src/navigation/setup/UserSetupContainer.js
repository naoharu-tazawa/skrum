import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import InlineEntityImagePicker from '../../components/InlineEntityImagePicker';
import { EntityType } from '../../util/EntityUtil';
import { loadImageSrc } from '../../util/ImageUtil';
import { postUserImage } from '../../project/GroupManagement/action';
import { setupUser } from '../action';
import styles from './Setup.css';

class UserSetupContainer extends Component {
  static propTypes = {
    userId: PropTypes.number.isRequired,
    isPosting: PropTypes.bool.isRequired,
    dispatchPostUserImage: PropTypes.func.isRequired,
    dispatchSetupUser: PropTypes.func.isRequired,
  };

  handleSubmit(e, { user }) {
    e.preventDefault();
    const { userId, dispatchSetupUser } = this.props;
    dispatchSetupUser(userId, { user })
      .then(({ error, payload: { message } = {} } = {}) => {
        if (error) { this.setState({ error: message }); }
        if (!error) { browserHistory.push('/'); }
      });
  }

  render() {
    const { userId, isPosting, dispatchPostUserImage } = this.props;
    const { user = {}, error } = this.state || {};
    return (
      <div className={styles.container}>
        <form
          className={styles.content}
          onSubmit={e => this.handleSubmit(e, { user })}
        >
          <h1>会員登録</h1>
          <section>
            <label className={styles.required}>姓</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, lastName: e.target.value } })}
            />
            <label className={styles.required}>名</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, firstName: e.target.value } })}
            />
          </section>
          <section>
            <label className={styles.required}>役職</label>
            <input
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, position: e.target.value } })}
            />
          </section>
          <section>
            <label>電話番号</label>
            <input
              maxLength={45}
              onChange={e => this.setState({ user: { ...user, phoneNumber: e.target.value } })}
            />
          </section>
          <section>
            <label>プロフィール画像</label>
            <InlineEntityImagePicker
              entity={{ id: userId, type: EntityType.USER }}
              avatarSize="120px"
              onSubmit={file => loadImageSrc(file).then(({ image, mimeType }) =>
                dispatchPostUserImage(userId, image, mimeType))}
            />
          </section>
          {!isPosting && error && <div className={styles.error}>{error}</div>}
          <section>
            {!isPosting && (
              <button
                type="submit"
                disabled={!user.lastName || !user.firstName || !user.position}
              >
                登録
              </button>)}
            {isPosting && <div className={styles.posting} />}
          </section>
        </form>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { userId } = state.auth;
  const { isPosting } = state.top;
  return { userId, isPosting };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPostUserImage = (id, image, mimeType) =>
    dispatch(postUserImage(id, image, mimeType));
  const dispatchSetupUser = (userId, setup) =>
    dispatch(setupUser(userId, setup));
  return { dispatchPostUserImage, dispatchSetupUser };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserSetupContainer);
