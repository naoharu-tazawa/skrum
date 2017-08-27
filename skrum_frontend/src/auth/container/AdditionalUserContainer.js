import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import { userJoin } from '../action';
import styles from './Registration.css';

class AdditionalUserContainer extends Component {
  static propTypes = {
    location: PropTypes.shape({ query: PropTypes.shape({}).isRequired }).isRequired,
    isPosting: PropTypes.bool.isRequired,
    dispatchUserJoin: PropTypes.func.isRequired,
  };

  handleSubmit(e, password, urltoken) {
    e.preventDefault();
    this.props.dispatchUserJoin(password, urltoken)
      .then(({ error, payload: { message } = {} } = {}) => {
        if (error) { this.setState({ error: message }); }
        if (!error) { browserHistory.push('/'); }
      });
  }

  render() {
    const { location: { query: { tkn: urltoken } }, isPosting } = this.props;
    if (!urltoken) return null;
    const { password, retype, error } = this.state || {};
    return (
      <div className={styles.container}>
        <form className={styles.content} onSubmit={e => this.handleSubmit(e, password, urltoken)}>
          <h1>追加ユーザ登録</h1>
          <section>
            <label>パスワード設定</label>
            <input type="password" maxLength={20} onChange={e => this.setState({ password: e.target.value })} />
          </section>
          <section>
            <label>パスワード再入力</label>
            <input type="password" maxLength={20} onChange={e => this.setState({ retype: e.target.value })} />
          </section>
          <section>
            {!isPosting && error && <div className={styles.error}>{error}</div>}
            {!isPosting && <button type="submit" disabled={!password || password !== retype}>送信</button>}
            {isPosting && <div className={styles.posting} />}
          </section>
        </form>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isPosting } = state.auth;
  return { isPosting };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchUserJoin = (password, token) =>
    dispatch(userJoin(password, token));
  return { dispatchUserJoin };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AdditionalUserContainer);
