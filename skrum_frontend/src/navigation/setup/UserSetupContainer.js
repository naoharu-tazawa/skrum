import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import { setupUser } from '../action';
import styles from './Setup.css';

class UserSetupContainer extends Component {
  static propTypes = {
    userId: PropTypes.number.isRequired,
    isPosting: PropTypes.bool.isRequired,
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
    const { isPosting } = this.props;
    const { user = {}, error } = this.state || {};
    return (
      <div className={styles.container}>
        <form
          className={styles.content}
          onSubmit={e => this.handleSubmit(e, { user })}
        >
          <h1>会員登録</h1>
          <section>
            <label>姓</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, lastName: e.target.value } })}
            />
            <label>名</label>
            <input
              className={styles.half}
              maxLength={120}
              onChange={e => this.setState({ user: { ...user, firstName: e.target.value } })}
            />
          </section>
          <section>
            <label>役職</label>
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
  const dispatchSetupUser = (userId, setup) =>
    dispatch(setupUser(userId, setup));
  return { dispatchSetupUser };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(UserSetupContainer);
