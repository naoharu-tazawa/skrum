import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import ReactPaginate from 'react-paginate';
import { debounce } from 'lodash';
import { groupsPropTypes } from './propTypes';
import GroupBar from './GroupBar';
import styles from './GroupList.css';

export default class GroupList extends PureComponent {

  static propTypes = {
    isFetchingGroups: PropTypes.bool.isRequired,
    count: PropTypes.number.isRequired,
    groups: groupsPropTypes.isRequired,
    currentRoleLevel: PropTypes.number.isRequired,
    dispatchFetchGroups: PropTypes.func.isRequired,
    dispatchDeleteGroup: PropTypes.func.isRequired,
  };

  componentDidMount() {
    this.props.dispatchFetchGroups('', 1);
    this.handleTextChange = debounce(text =>
      this.setState({ text, page: 1 }, () => this.props.dispatchFetchGroups(text, 1)), 1000);
  }

  handlePageClick = ({ selected }) => {
    const { text = '' } = this.state || {};
    this.setState({ page: selected + 1 }, () =>
      this.props.dispatchFetchGroups(text, selected + 1));
  };

  render() {
    const { isFetchingGroups, count, groups, currentRoleLevel, dispatchDeleteGroup } = this.props;
    return (
      <section className={styles.group_list}>
        <div className={styles.title}>グループ検索</div>
        <input
          className={styles.input}
          type="text"
          onChange={({ target }) => this.handleTextChange(target.value)}
        />
        {isFetchingGroups && <div className={styles.fetching} />}
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
