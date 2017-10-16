import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import ReactPaginate from 'react-paginate';
import { debounce } from 'lodash';
import { usersPropTypes } from './propTypes';
import UserBar from './UserBar';
import styles from './UserList.css';

export default class UserList extends PureComponent {

  static propTypes = {
    isFetchingUsers: PropTypes.bool.isRequired,
    count: PropTypes.number.isRequired,
    users: usersPropTypes.isRequired,
    currentUserId: PropTypes.number.isRequired,
    currentRoleLevel: PropTypes.number.isRequired,
    dispatchFetchUsers: PropTypes.func.isRequired,
    dispatchResetPassword: PropTypes.func.isRequired,
    dispatchAssignRole: PropTypes.func.isRequired,
    dispatchDeleteUser: PropTypes.func.isRequired,
  };

  componentDidMount() {
    const { count, dispatchFetchUsers } = this.props;
    if (!count) dispatchFetchUsers('', 1);
    this.handleTextChange = debounce(text =>
      this.setState({ text, page: 1 }, () => dispatchFetchUsers(text, 1)), 1000);
  }

  handlePageClick = ({ selected }) => {
    const { text = '' } = this.state || {};
    this.setState({ page: selected + 1 }, () =>
      this.props.dispatchFetchUsers(text, selected + 1));
  };

  render() {
    const { isFetchingUsers, count, users, currentUserId, currentRoleLevel,
      dispatchResetPassword, dispatchAssignRole, dispatchDeleteUser } = this.props;
    return (
      <section className={styles.member_list}>
        <div className={styles.title}>ユーザ検索</div>
        <input
          className={styles.input}
          type="text"
          onChange={({ target }) => this.handleTextChange(target.value)}
        />
        {isFetchingUsers && <div className={styles.fetching} />}
        <div className={styles.count}>
          {isFetchingUsers ? '\u00A0' : `検索結果：${count}件`}
        </div>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <UserBar header />
          {users.map(user =>
            <UserBar
              key={user.id}
              {...{ user, currentUserId, currentRoleLevel }}
              {...{ dispatchResetPassword, dispatchAssignRole, dispatchDeleteUser }}
            />)}
        </div>
        <div className={styles.paging}>
          {count > 0 && (
            <ReactPaginate
              previousLabel="＜ 前のページ"
              nextLabel="次のページ ＞"
              breakLabel={<a href="">...</a>}
              breakClassName="break-me"
              pageCount={Math.ceil(count / 20)}
              marginPagesDisplayed={2}
              pageRangeDisplayed={5}
              onPageChange={this.handlePageClick}
              containerClassName={styles.pagination}
              pageClassName={styles.page}
              previousClassName={styles.previous}
              nextClassName={styles.next}
              activeClassName={styles.active}
              disabledClassName={styles.disabled}
            />)}
        </div>
      </section>);
  }
}
