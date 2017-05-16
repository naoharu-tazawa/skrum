import React, { Component } from 'react';
import { connect } from 'react-redux';
import PropTypes from 'prop-types';
import UserInfoContainer from './UserInfo/UserInfoContainer';
import GroupInfoContainer from './GroupInfo/GroupInfoContainer';
import CompanyInfoContainer from './CompanyInfo/CompanyInfoContainer';
import { UserOKRListContainer, GroupOKRListContainer, CompanyOKRListContainer } from './OKRList/OKRListContainer';
import { fetchUserBasics, fetchGroupBasics, fetchCompanyBasics } from './action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import styles from './OKRContainer.css';

class OKRContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    subject: PropTypes.string,
    dispatchFetchUserBasics: PropTypes.func,
    dispatchFetchGroupBasics: PropTypes.func,
    dispatchFetchCompanyBasics: PropTypes.func,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchBasics(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchBasics(pathname);
    }
  }

  fetchBasics(pathname) {
    const {
      dispatchFetchUserBasics,
      dispatchFetchGroupBasics,
      dispatchFetchCompanyBasics,
    } = this.props;
    const { section, id, timeframeId } = explodePath(pathname);
    switch (section) {
      case 'user':
        dispatchFetchUserBasics(id, timeframeId);
        break;
      case 'group':
        dispatchFetchGroupBasics(id, timeframeId);
        break;
      case 'company':
        dispatchFetchCompanyBasics(id, timeframeId);
        break;
      default:
        break;
    }
  }

  renderInfoContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserInfoContainer />;
      case 'group':
        return <GroupInfoContainer />;
      case 'company':
        return <CompanyInfoContainer />;
      default:
        return null;
    }
  }

  renderOKRListContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserOKRListContainer />;
      case 'group':
        return <GroupOKRListContainer />;
      case 'company':
        return <CompanyOKRListContainer />;
      default:
        return null;
    }
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.userInfo}>
          {this.renderInfoContainer()}
        </div>
        <div className={styles.okrList}>
          {this.renderOKRListContainer()}
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { isFetching = false } = state.basics || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  return { isFetching, pathname };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchUserBasics = (userId, timeframeId) =>
    dispatch(fetchUserBasics(userId, timeframeId));
  const dispatchFetchGroupBasics = (groupId, timeframeId) =>
    dispatch(fetchGroupBasics(groupId, timeframeId));
  const dispatchFetchCompanyBasics = (companyId, timeframeId) =>
    dispatch(fetchCompanyBasics(companyId, timeframeId));
  return {
    dispatchFetchUserBasics,
    dispatchFetchGroupBasics,
    dispatchFetchCompanyBasics,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OKRContainer);
