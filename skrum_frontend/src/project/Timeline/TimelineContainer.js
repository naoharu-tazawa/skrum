import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { reset } from 'redux-form';
import NewTimeline, { formName } from './NewTimeline';
import PostListContainer from './PostList/PostListContainer';
import { errorType } from '../../util/PropUtil';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { fetchGroupPosts, fetchMoreGroupPosts, postGroupPosts } from './action';
import styles from './TimelineContainer.css';

class TimelineContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    isFetchingMore: PropTypes.bool,
    hasMorePosts: PropTypes.bool,
    isPosting: PropTypes.bool,
    pathname: PropTypes.string,
    dispatchFetchGroupPosts: PropTypes.func,
    dispatchFetchMoreGroupPosts: PropTypes.func,
    dispatchPostGroupPosts: PropTypes.func,
    dispatchResetForm: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
    const { pathname, dispatchFetchGroupPosts } = this.props;
    if (isPathFinal(pathname)) {
      dispatchFetchGroupPosts();
    }
  }

  componentWillReceiveProps(next) {
    const { pathname, dispatchFetchGroupPosts } = next;
    if (this.props.pathname !== pathname) {
      dispatchFetchGroupPosts();
    }
    this.result(next);
  }

  result(props = this.props) {
    const { isPosting, error, dispatchResetForm } = props;
    if (!isPosting && !error) {
      dispatchResetForm();
    }
  }

  handleSubmit({ post, disclosureType }) {
    if (post === undefined) return Promise.resolve();
    return this.props.dispatchPostGroupPosts(post, disclosureType);
  }

  render() {
    const { isFetching, isFetchingMore, hasMorePosts, dispatchFetchMoreGroupPosts,
      isPosting, error } = this.props;
    if (isFetching) {
      return <span className={styles.spinner} />; // MUST be other than div here to scroll to top
    }
    return (
      <div className={styles.container}>
        <div className={`${styles.inner} ${styles.timeline}`}>
          <section className={styles.new_post_box}>
            <NewTimeline
              isPosting={isPosting}
              error={error}
              onSubmit={this.handleSubmit.bind(this)}
            />
          </section>
          <PostListContainer {...{ isFetchingMore, hasMorePosts, dispatchFetchMoreGroupPosts }} />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching, isFetchingMore, hasMorePosts, isPosting, error } = state.timeline || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, isFetchingMore, hasMorePosts, isPosting, error, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchGroupPosts = groupId =>
    dispatch(fetchGroupPosts(groupId));
  const dispatchFetchMoreGroupPosts = (groupId, before) =>
    dispatch(fetchMoreGroupPosts(groupId, before));
  const dispatchPostGroupPosts = (groupId, post, disclosureType) =>
    dispatch(postGroupPosts(groupId, post, disclosureType));
  const dispatchResetForm = () => dispatch(reset(formName));
  return {
    dispatchFetchGroupPosts,
    dispatchFetchMoreGroupPosts,
    dispatchPostGroupPosts,
    dispatchResetForm,
  };
};

const mergeProps = (state, dispatch, props) => {
  const { pathname, dispatchFetchGroupPosts, dispatchFetchMoreGroupPosts, dispatchPostGroupPosts,
    dispatchResetForm } = dispatch;
  const { id } = explodePath(pathname);
  return {
    ...state,
    ...props,
    dispatchFetchGroupPosts: () => dispatchFetchGroupPosts(id),
    dispatchFetchMoreGroupPosts: before => dispatchFetchMoreGroupPosts(id, before),
    dispatchPostGroupPosts: (post, disclosureType) =>
      dispatchPostGroupPosts(id, post, disclosureType),
    dispatchResetForm,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(TimelineContainer);
