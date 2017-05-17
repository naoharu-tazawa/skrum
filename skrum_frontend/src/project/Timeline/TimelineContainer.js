import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import PostListContainer from './PostList/PostListContainer';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import styles from './TimelineContainer.css';
import { fetchGroupPosts, postGroupPosts } from './action';

class TimelineContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    pathname: PropTypes.string,
    dispatchFetchGroupPosts: PropTypes.func,
    dispatchPostGroupPosts: PropTypes.func,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      const { dispatchFetchGroupPosts } = this.props;
      const { id } = explodePath(pathname);
      dispatchFetchGroupPosts(id);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      const { dispatchFetchGroupPosts } = this.props;
      const { id } = explodePath(pathname);
      dispatchFetchGroupPosts(id);
    }
  }

  handleSubmit(e) {
    const { pathname } = this.props;
    const { id } = explodePath(pathname);
    const target = e.target;
    e.preventDefault();
    this.props.dispatchPostGroupPosts(
      id,
      target.post.value.trim(),
      '1',
    );
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    const { pathname } = this.props;
    return (
      <div className={styles.container}>
        <div className={styles.userInfo}>
          <form onSubmit={e => this.handleSubmit(e)}>
            <div className={styles.postHeader}>新規投稿作成</div>
            <div className={styles.postBody}>
              <div className={styles.ownerImage} />
              <textarea id="post" className={styles.postInput} />
              <button className={styles.postButton}>投稿</button>
            </div>
          </form>
        </div>
        <main className={styles.okrList}>
          <PostListContainer pathname={pathname} />
        </main>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching = false } = state.timeline || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchGroupPosts = groupId => dispatch(fetchGroupPosts(groupId));
  const dispatchPostGroupPosts = (groupId, post, disclosureType) =>
    dispatch(postGroupPosts(groupId, post, disclosureType));
  return { dispatchFetchGroupPosts, dispatchPostGroupPosts };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(TimelineContainer);
