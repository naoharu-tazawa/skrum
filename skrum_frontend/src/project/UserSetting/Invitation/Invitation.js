import React, { Component } from 'react';
import { rolesPropTypes } from './propTypes';
import styles from './Invitation.css';

export default class Invitation extends Component {

  static propTypes = {
    items: rolesPropTypes.isRequired,
  };

  handleSubmit() {
  }

  render() {
    // const { items } = this.props;
    // const { id, name } = items;
    return (
      <section>
        <div className={styles.title}>ユーザ招待</div>
        <form onSubmit={e => this.handleSubmit(e)}>
          <span><input className={styles.input} type="text" placeholder="メールアドレスを入力して招待メールを送る" /></span>
          <span><button className={styles.btn}>招待する</button></span>
        </form>
      </section>
    );
  }
}
