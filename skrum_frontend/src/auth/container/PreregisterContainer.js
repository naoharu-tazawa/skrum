import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import { preregister } from '../action';
import styles from './PreregisterContainer.css';

class PreregisterContainer extends Component {
  static propTypes = {
    params: PropTypes.shape({
      status: PropTypes.string,
    }),
    preregistered: PropTypes.shape({
      emailAddress: PropTypes.string.isRequired,
      subdomain: PropTypes.string.isRequired,
    }),
    isPreregistering: PropTypes.bool.isRequired,
    dispatchPreregister: PropTypes.func.isRequired,
  };

  handleSubmit(e, email, subdomain) {
    e.preventDefault();
    this.props.dispatchPreregister(email, subdomain)
      .then(({ error, payload: { message } = {} } = {}) => {
        if (error) { this.setState({ error: message }); }
        if (!error) { browserHistory.push('/preregister/success'); }
      });
  }

  render() {
    const { params: { status } = {}, isPreregistering, preregistered = {} } = this.props;
    if (status) {
      return (
        <div className={styles.container}>
          <div className={styles.content}>
            <p>新規アカウント作成確認メールを以下のメールアドレスに送信しました。</p>
            <p>Eメールアドレス：<strong>{preregistered.emailAddress}</strong></p>
            <p>サブドメイン　&nbsp;&nbsp;：<strong>{preregistered.subdomain}</strong></p>
            <br />
            <p>メールに記載されているURLから本登録を行なってください。</p>
            <br />
            <br />
            <p>もしメールアドレスを間違えて入力した場合は新規アカウント作成画面に戻り、再度登録を行なってください。</p>
            <br />
            <button onClick={browserHistory.goBack}>戻る</button>
          </div>
        </div>);
    }
    const { email, subdomain, error } = this.state || {};
    return (
      <div className={styles.container}>
        <form className={styles.content} onSubmit={e => this.handleSubmit(e, email, subdomain)}>
          <h1>新規アカウント作成</h1>
          <section>
            <label>Eメールアドレス</label>
            <input type="email" onChange={e => this.setState({ email: e.target.value })} />
          </section>
          <section>
            <label>サブドメイン</label>
            <input type="text" onChange={e => this.setState({ subdomain: e.target.value })} />
          </section>
          {error && <div className={styles.error}>{error}</div>}
          {!isPreregistering && <button type="submit" disabled={!email || !subdomain}>送信</button>}
          {isPreregistering && <div className={styles.posting} />}
        </form>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isPreregistering, preregistered } = state.auth;
  return { isPreregistering, preregistered };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPreregister = (email, subdomain) =>
    dispatch(preregister(email, subdomain));
  return { dispatchPreregister };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PreregisterContainer);
