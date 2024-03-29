import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import { userJoin } from '../action';
import styles from './Registration.css';

class AdditionalUserContainer extends Component {
  static propTypes = {
    location: PropTypes.shape({ query: PropTypes.shape({}).isRequired }).isRequired,
    dispatchUserJoin: PropTypes.func.isRequired,
  };

  handleSubmit(e, password, urltoken) {
    e.preventDefault();
    this.setState({ isPosting: true });
    this.props.dispatchUserJoin(password, urltoken)
      .then(({ error, payload: { message } = {} } = {}) =>
        (error ? this.setState({ isPosting: false, error: message }) : browserHistory.replace('/')));
  }

  render() {
    const { location: { query: { tkn: urltoken } } } = this.props;
    if (!urltoken) return null;
    const { password, retype, error, isPosting } = this.state || {};
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
          <div className={styles.notice}>半角英数字8字以上20字以下で、アルファベット・数字をそれぞれ1字以上含めてください。</div>
          <section>
            {!isPosting && error && <div className={styles.error}>{error}</div>}
            {!isPosting && <button type="submit" disabled={!password || password !== retype}>送信</button>}
            {isPosting && <div className={styles.posting} />}
          </section>
        </form>
      </div>);
  }
}

const mapDispatchToProps = (dispatch) => {
  const dispatchUserJoin = (password, token) =>
    dispatch(userJoin(password, token));
  return { dispatchUserJoin };
};

export default connect(
  null,
  mapDispatchToProps,
)(AdditionalUserContainer);
