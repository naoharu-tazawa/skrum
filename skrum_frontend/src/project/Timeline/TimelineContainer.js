import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { reset } from 'redux-form';
import NewTimeline, { formName } from './NewTimeline';
import PostListContainer from './PostList/PostListContainer';
import { errorType } from '../../util/PropUtil';
import { isPathFinal } from '../../util/RouteUtil';
import { fetchGroupPosts, fetchMoreGroupPosts, postGroupPost,
  fetchCompanyPosts, fetchMoreCompanyPosts, postCompanyPost } from './action';
import styles from './TimelineContainer.css';

class TimelineContainer extends Component {

  static propTypes = {
    subject: PropTypes.oneOf(['group', 'company']).isRequired,
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
    const { isFetching, isFetchingMore, hasMorePosts, dispatchFetchMorePosts,
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
  const dispatchFetchGroupPosts = groupId =>
    dispatch(fetchGroupPosts(groupId));
  const dispatchFetchMoreGroupPosts = (groupId, before) =>
    dispatch(fetchMoreGroupPosts(groupId, before));
  const dispatchPostGroupPost = (groupId, post, disclosureType) =>
    dispatch(postGroupPost(groupId, post, disclosureType));
  const dispatchFetchCompanyPosts = companyId =>
    dispatch(fetchCompanyPosts(companyId));
  const dispatchFetchMoreCompanyPosts = (companyId, before) =>
    dispatch(fetchMoreCompanyPosts(companyId, before));
  const dispatchPostCompanyPost = (companyId, post, disclosureType) =>
    dispatch(postCompanyPost(companyId, post, disclosureType));
  const dispatchResetForm = () => dispatch(reset(formName));
  return {
    dispatchFetchGroupPosts,
    dispatchFetchMoreGroupPosts,
    dispatchPostGroupPost,
    dispatchFetchCompanyPosts,
    dispatchFetchMoreCompanyPosts,
    dispatchPostCompanyPost,
    dispatchResetForm,
  };
};

const mergeProps = (state, {
  dispatchFetchGroupPosts,
  dispatchFetchMoreGroupPosts,
  dispatchPostGroupPost,
  dispatchFetchCompanyPosts,
  dispatchFetchMoreCompanyPosts,
  dispatchPostCompanyPost,
  dispatchResetForm,
}, props) => ({
  ...state,
  ...props,
  dispatchFetchPosts: () =>
    (props.subject === 'group' ?
      dispatchFetchGroupPosts(props.id) :
      dispatchFetchCompanyPosts(props.id)),
  dispatchFetchMorePosts: before =>
    (props.subject === 'group' ?
      dispatchFetchMoreGroupPosts(props.id, before) :
      dispatchFetchMoreCompanyPosts(props.id, before)),
  dispatchPostPost: (post, disclosureType) =>
    (props.subject === 'group' ?
      dispatchPostGroupPost(props.id, post, disclosureType) :
      dispatchPostCompanyPost(props.id, post, disclosureType)),
  dispatchResetForm,
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
  mergeProps,
)(TimelineContainer);
