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
        <div className={`${styles.inner} ${styles.timeline}`}>
          <section className={styles.new_post_box}>
            <form onSubmit={e => this.handleSubmit(e)}>
              <h1 className={styles.ttl_section}>新規投稿作成</h1>
              <div className={styles.cont_box}>
                <div className={styles.user_name}>
                  <dl>
                    <dt><img src="/img/common/icn_user.png" alt="" /></dt>
                    <dd />
                  </dl>
                </div>
                <div className={styles.text_area}><textarea id="post" placeholder="仕事の状況はどうですか？" /></div>
                <div className={styles.btn}><button>投稿する</button></div>
              </div>
            </form>
          </section>
          <PostListContainer pathname={pathname} />
        </div>
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
