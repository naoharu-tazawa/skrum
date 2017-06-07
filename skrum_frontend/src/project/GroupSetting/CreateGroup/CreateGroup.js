import React, { Component } from 'react';
import { rolesPropTypes } from './propTypes';
import styles from './CreateGroup.css';

export default class CreateGroup extends Component {

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
        <div>グループ作成</div>
        <form onSubmit={e => this.handleSubmit(e)}>
          <div>
            <span><input className={styles.input} type="text" placeholder="グループ名" /></span>
            <span>
              <input className={styles.radio} type="radio" name="department" value="1" /><span>部署</span>
              <input className={styles.radio} type="radio" name="team" value="2" /><span>チーム</span>
            </span>
          </div>
          <div>
            <span className={styles.group_search_label}>所属先グループ検索：</span>
            <span><input className={styles.group_search} type="text" /></span>
            <span><button className={styles.btn}>作成する</button></span>
          </div>
        </form>
      </section>
    );
  }
}
