import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import ReactPaginate from 'react-paginate';
import { debounce } from 'lodash';
import { groupsPropTypes } from './propTypes';
import GroupBar from './GroupBar';
import styles from './GroupList.css';

export default class GroupList extends PureComponent {

  static propTypes = {
    keyword: PropTypes.string,
    pageNo: PropTypes.number,
    isFetchingGroups: PropTypes.bool.isRequired,
    count: PropTypes.number.isRequired,
    groups: groupsPropTypes.isRequired,
    currentRoleLevel: PropTypes.number.isRequired,
    dispatchFetchGroups: PropTypes.func.isRequired,
    dispatchDeleteGroup: PropTypes.func.isRequired,
  };

  componentDidMount() {
    const { dispatchFetchGroups } = this.props;
    dispatchFetchGroups('', 1);
    this.handleTextChange = debounce(keyword => dispatchFetchGroups(keyword, 1), 1000);
  }

  handlePageClick = ({ selected }) => {
    const { keyword = '' } = this.props;
    this.props.dispatchFetchGroups(keyword, selected + 1);
  };

  render() {
    const { pageNo = 1, isFetchingGroups, count, groups, currentRoleLevel,
      dispatchDeleteGroup } = this.props;
    return (
      <section className={styles.group_list}>
        <div className={styles.title}>グループ検索</div>
        <input
          className={styles.input}
          type="text"
          onChange={({ target }) => this.handleTextChange(target.value)}
        />
        {isFetchingGroups && <div className={styles.fetching} />}
        <div className={styles.count}>
          {isFetchingGroups ? '\u00A0' : `検索結果：${count}件`}
        </div>
        <div className={`${styles.cont_box} ${styles.cf}`}>
          <GroupBar header />
          {groups.map(group =>
            <GroupBar key={group.id} {...{ group, currentRoleLevel, dispatchDeleteGroup }} />)}
        </div>
        <div className={styles.paging}>
          {count > 0 && (
            <ReactPaginate
              previousLabel="＜ 前のページ"
              nextLabel="次のページ ＞"
              breakLabel={<a href="">...</a>}
              breakClassName="break-me"
              forcePage={pageNo - 1}
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
