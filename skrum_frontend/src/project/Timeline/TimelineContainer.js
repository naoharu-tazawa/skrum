import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { reset } from 'redux-form';
import NewTimeline, { formName } from './NewTimeline';
import PostListContainer from './PostList/PostListContainer';
import { errorType } from '../../util/PropUtil';
import { isPathFinal } from '../../util/RouteUtil';
import { fetchUserPosts, fetchMoreUserPosts, fetchGroupPosts, fetchMoreGroupPosts,
  fetchCompanyPosts, fetchMoreCompanyPosts, postGroupPost, postCompanyPost } from './action';
import styles from './TimelineContainer.css';

class TimelineContainer extends Component {

  static propTypes = {
    subject: PropTypes.oneOf(['user', 'group', 'company']).isRequired,
    id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
    isFetching: PropTypes.bool,
    isFetchingMore: PropTypes.bool,
    hasMorePosts: PropTypes.bool,
    isPosting: PropTypes.bool,
    pathname: PropTypes.string,
    dispatchFetchPosts: PropTypes.func,
    dispatchFetchMorePosts: PropTypes.func,
    dispatchPostPost: PropTypes.func,
    dispatchResetForm: PropTypes.func,
    error: errorType,
  };

  componentWillMount() {
    const { pathname, dispatchFetchPosts } = this.props;
    if (isPathFinal(pathname)) {
      dispatchFetchPosts();
    }
  }

  componentWillReceiveProps(next) {
    const { pathname, dispatchFetchPosts } = next;
    if (this.props.pathname !== pathname) {
      dispatchFetchPosts();
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
    return this.props.dispatchPostPost(post, disclosureType);
  }

  render() {
    const { subject, isFetching, isFetchingMore, hasMorePosts, dispatchFetchMorePosts,
      isPosting, error } = this.props;
    if (isFetching) {
      return <span className={styles.spinner} />; // MUST be other than div here to scroll to top
    }
    return (
      <div className={styles.container}>
        <div className={`${styles.inner} ${styles.timeline}`}>
          {subject !== 'user' && (
            <section className={styles.new_post_box}>
              <NewTimeline
                isPosting={isPosting}
                error={error}
                onSubmit={this.handleSubmit.bind(this)}
              />
            </section>)}
          <PostListContainer {...{ isFetchingMore, hasMorePosts, dispatchFetchMorePosts }} />
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
  const dispatchFetchUserPosts = userId =>
    dispatch(fetchUserPosts(userId));
  const dispatchFetchMoreUserPosts = (userId, before) =>
    dispatch(fetchMoreUserPosts(userId, before));
  const dispatchFetchGroupPosts = groupId =>
    dispatch(fetchGroupPosts(groupId));
  const dispatchFetchMoreGroupPosts = (groupId, before) =>
    dispatch(fetchMoreGroupPosts(groupId, before));
  const dispatchFetchCompanyPosts = companyId =>
    dispatch(fetchCompanyPosts(companyId));
  const dispatchFetchMoreCompanyPosts = (companyId, before) =>
    dispatch(fetchMoreCompanyPosts(companyId, before));
  const dispatchPostGroupPost = (groupId, post, disclosureType) =>
    dispatch(postGroupPost(groupId, post, disclosureType));
  const dispatchPostCompanyPost = (companyId, post, disclosureType) =>
    dispatch(postCompanyPost(companyId, post, disclosureType));
  const dispatchResetForm = () => dispatch(reset(formName));
  return {
    dispatchFetchUserPosts,
    dispatchFetchMoreUserPosts,
    dispatchFetchGroupPosts,
    dispatchFetchMoreGroupPosts,
    dispatchFetchCompanyPosts,
    dispatchFetchMoreCompanyPosts,
    dispatchPostGroupPost,
    dispatchPostCompanyPost,
    dispatchResetForm,
  };
};

const mergeProps = (state, {
  dispatchFetchUserPosts,
  dispatchFetchMoreUserPosts,
  dispatchFetchGroupPosts,
  dispatchFetchMoreGroupPosts,
  dispatchFetchCompanyPosts,
  dispatchFetchMoreCompanyPosts,
  dispatchPostGroupPost,
  dispatchPostCompanyPost,
  dispatchResetForm,
}, props) => ({
  ...state,
  ...props,
  dispatchFetchPosts: () => ({
    user: dispatchFetchUserPosts,
    group: dispatchFetchGroupPosts,
    company: dispatchFetchCompanyPosts }[props.subject])(props.id),
  dispatchFetchMorePosts: before => ({
    user: dispatchFetchMoreUserPosts,
    group: dispatchFetchMoreGroupPosts,
    company: dispatchFetchMoreCompanyPosts }[props.subject])(props.id, before),
  dispatchPostPost: (post, disclosureType) => ({
    group: dispatchPostGroupPost,
    company: dispatchPostCompanyPost }[props.subject])(props.id, post, disclosureType),
  dispatchResetForm,
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(TimelineContainer);
