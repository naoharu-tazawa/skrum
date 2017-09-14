import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { browserHistory } from 'react-router';
import { preregister } from '../action';
import styles from './Registration.css';

class PreregisterContainer extends Component {
  static propTypes = {
    location: PropTypes.shape({ query: PropTypes.shape({}).isRequired }).isRequired,
    preregistered: PropTypes.shape({
      emailAddress: PropTypes.string.isRequired,
      subdomain: PropTypes.string.isRequired,
    }),
    dispatchPreregister: PropTypes.func.isRequired,
  };

  handleSubmit(e, email, subdomain) {
    e.preventDefault();
    this.setState({ isPreregistering: true });
    this.props.dispatchPreregister(email, subdomain)
      .then(({ error, payload: { message } = {} } = {}) => {
        this.setState({ isPreregistering: false, error: error && message });
        if (!error) browserHistory.push('/preregister?result=success');
      });
  }

  render() {
    const { location: { query: { result } }, preregistered = {} } = this.props;
    const { isPreregistering } = this.state || {};
    if (result === 'success') {
      return (
        <div className={styles.container}>
          <div className={styles.content}>
            <p>新規アカウント作成確認メールを以下のEメールアドレスに送信しました。</p>
            <p>Eメールアドレス：<strong>{preregistered.emailAddress}</strong></p>
            <p>サブドメイン　&nbsp;&nbsp;：<strong>{preregistered.subdomain}</strong></p>
            <br />
            <p>Eメールに記載されているURLから本登録を行なってください。</p>
            <br />
            <br />
            <p>もしEメールアドレスまたは希望サブドメインを間違えて入力した場合は新規アカウント作成画面に戻り、再度登録を行なってください。</p>
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
          <div className={styles.notice}>サブドメインとは御社専用のSkrumページにアクセスするために必要なものです。</div>
          <div className={styles.notice}>`（例：https://[サブドメイン].skrum.jp）`</div>
          <div className={styles.notice}>小文字アルファベット、数字、ハイフンのみを使い、3文字以上28文字以下で指定してください。</div>
          <section>
            {!isPreregistering && error && <div className={styles.error}>{error}</div>}
            {!isPreregistering && <button type="submit" disabled={!email || !subdomain}>送信</button>}
            {isPreregistering && <div className={styles.posting} />}
          </section>
        </form>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { preregistered } = state.auth;
  return { preregistered };
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
