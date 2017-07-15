import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { reset } from 'redux-form';
import PostListContainer from './PostList/PostListContainer';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { errorType } from '../../util/PropUtil';
import styles from './TimelineContainer.css';
import { fetchGroupPosts, postGroupPosts } from './action';
import Form from './Form';

class TimelineContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    isPosting: PropTypes.bool,
    pathname: PropTypes.string,
    dispatchFetchGroupPosts: PropTypes.func,
    dispatchPostGroupPosts: PropTypes.func,
    dispatchResetForm: PropTypes.func,
    error: errorType,
  };

  constructor(props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

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
    this.result(next);
  }

  result(props = this.props) {
    const { isPosting, error, dispatchResetForm } = props;
    if (!isPosting && !error) {
      dispatchResetForm();
    }
  }

  handleSubmit(data) {
    if (data.post !== undefined) {
      const { pathname } = this.props;
      const { id } = explodePath(pathname);
      this.props.dispatchPostGroupPosts(
        id,
        data.post,
        '1',
      );
    }
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    const { isPosting, error, pathname } = this.props;
    return (
      <div className={styles.container}>
        <div className={`${styles.inner} ${styles.timeline}`}>
          <section className={styles.new_post_box}>
            <Form
              isPosting={isPosting}
              error={error}
              onSubmit={this.handleSubmit}
            />
          </section>
          <PostListContainer pathname={pathname} />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching = false, isPosting = false, error } = state.timeline || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, isPosting, error, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchGroupPosts = groupId => dispatch(fetchGroupPosts(groupId));
  const dispatchPostGroupPosts = (groupId, post, disclosureType) =>
    dispatch(postGroupPosts(groupId, post, disclosureType));
  const dispatchResetForm = () => dispatch(reset('form'));
  return { dispatchFetchGroupPosts, dispatchPostGroupPosts, dispatchResetForm };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(TimelineContainer);
