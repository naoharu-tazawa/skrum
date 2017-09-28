import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import UserInfoContainer from './UserInfo/UserInfoContainer';
import GroupInfoContainer from './GroupInfo/GroupInfoContainer';
import CompanyInfoContainer from './CompanyInfo/CompanyInfoContainer';
import { UserOKROverallChartsContainer, GroupOKROverallChartsContainer, CompanyOKROverallChartsContainer } from './OKRList/OKROverallChartsContainer';
import { UserOKRListContainer, GroupOKRListContainer, CompanyOKRListContainer } from './OKRList/OKRListContainer';
import { UserOKRAlignmentsInfoContainer, GroupOKRAlignmentsInfoContainer } from './OKRAlignmentsInfo/OKRAlignmentsInfoContainer';
import OKRAlignmentsInfo from './OKRAlignmentsInfo/OKRAlignmentsInfo';
import OKRDetailsContainer from '../OKRDetails/OKRDetailsContainer';
import { fetchUserBasics, fetchGroupBasics, fetchCompanyBasics, changeOkrOwner, copyOkr, deleteOkr } from './action';
import { explodePath, implodePath, comparePath, isPathFinal } from '../../util/RouteUtil';
import styles from './OKRContainer.css';

class OKRContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool.isRequired,
    subject: PropTypes.string.isRequired,
    dispatchFetchUserBasics: PropTypes.func.isRequired,
    dispatchFetchGroupBasics: PropTypes.func.isRequired,
    dispatchFetchCompanyBasics: PropTypes.func.isRequired,
    dispatchChangeOkrOwner: PropTypes.func.isRequired,
    dispatchCopyOkr: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired,
    pathname: PropTypes.string.isRequired,
    okrIds: PropTypes.arrayOf(PropTypes.number).isRequired,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchBasics(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (!comparePath(this.props.pathname, pathname, { basicOnly: true })) {
      this.fetchBasics(pathname);
    }
  }

  fetchBasics(pathname) {
    const {
      subject,
      dispatchFetchUserBasics,
      dispatchFetchGroupBasics,
      dispatchFetchCompanyBasics,
    } = this.props;
    const { id, timeframeId } = explodePath(pathname);
    switch (subject) {
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

  renderChartContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserOKROverallChartsContainer />;
      case 'group':
        return <GroupOKROverallChartsContainer />;
      case 'company':
        return <CompanyOKROverallChartsContainer />;
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

  renderOKRAlignmentsInfoContainer() {
    switch (this.props.subject) {
      case 'user':
        return <UserOKRAlignmentsInfoContainer />;
      case 'group':
        return <GroupOKRAlignmentsInfoContainer />;
      default:
        return null;
    }
  }

  render() {
    const { isFetching, pathname, okrIds, dispatchChangeOkrOwner, dispatchCopyOkr,
      dispatchDeleteOkr } = this.props;
    if (isFetching) {
      return <span className={styles.spinner} />;
    }
    const { showAlignmentsInfo } = this.state || {};
    const { aspect, aspectId: okrId, ...basicPath } = explodePath(pathname);
    const { subject } = basicPath;
    const okrIndex = okrId && okrIds.indexOf(okrId);
    const prevOkrId = okrIndex > 0 && okrIds[okrIndex - 1];
    const nextOkrId = okrIndex !== -1 && okrIndex < okrIds.length - 1 && okrIds[okrIndex + 1];
    const showDetails = aspect === 'o' && okrId;
    return (
      <div className={styles.container}>
        <div style={!showDetails ? { display: 'none' } : {}}>
          <div className={styles.navigator}>
            <Link to={implodePath(basicPath)} className={styles.backLink}>目標一覧へ戻る</Link>
            <Link
              to={nextOkrId ? implodePath({ aspect: 'o', aspectId: nextOkrId, ...basicPath }) : ''}
              className={`${styles.naviLink} ${styles.nextLink} ${!nextOkrId ? styles.disabled : ''}`}
            >
              次の目標
            </Link>
            <Link
              to={prevOkrId ? implodePath({ aspect: 'o', aspectId: prevOkrId, ...basicPath }) : ''}
              className={`${styles.naviLink} ${styles.prevLink} ${!prevOkrId ? styles.disabled : ''}`}
            >
              前の目標
            </Link>
          </div>
          {showDetails && (
            <OKRDetailsContainer
              {...{ subject, dispatchChangeOkrOwner, dispatchCopyOkr, dispatchDeleteOkr }}
            />)}
        </div>
        <article style={showDetails ? { display: 'none' } : {}}>
          <section className={`${styles.overall_info} ${styles.cf}`}>
            <div className={`${styles.basic_info} ${styles.h_line} ${styles.floatL}`}>
              <div className={styles.ttl}><h2>基本情報</h2></div>
              {this.renderInfoContainer()}
            </div>
            <div className={`${styles.overall_situation} ${styles.h_line} ${styles.floatR}`}>
              <div className={styles.ttl}><h2>全体の状況</h2></div>
              {this.renderChartContainer()}
            </div>
          </section>
          <section className={styles.list}>
            <div className={styles.ttl}>
              <nav>
                <ul>
                  <li>
                    <Link
                      className={showAlignmentsInfo || subject === 'company' ? '' : styles.active}
                      onClick={() => this.setState({ showAlignmentsInfo: false })}
                      to={pathname}
                    >
                      目標一覧
                    </Link>
                  </li>
                  <li>
                    {subject === 'company' ? null : (
                      <Link
                        className={showAlignmentsInfo ? styles.active : ''}
                        onClick={() => this.setState({ showAlignmentsInfo: true })}
                        to={pathname}
                      >
                        目標の紐付け先
                      </Link>)}
                  </li>
                </ul>
              </nav>
            </div>
            <div style={showAlignmentsInfo ? { display: 'none' } : {}}>
              {this.renderOKRListContainer()}
            </div>
            <div style={showAlignmentsInfo ? {} : { display: 'none' }}>
              <OKRAlignmentsInfo display="header" />
              {this.renderOKRAlignmentsInfoContainer()}
            </div>
          </section>
        </article>
      </div>
    );
  }
}

const mapStateToProps = (state, { subject }) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { isFetching, [subject]: basics = {} } = state.basics || {};
  const { okrs = [] } = basics || {};
  const okrIds = okrs.map(({ okrId }) => okrId);
  return { isFetching, pathname, okrIds };
};

const mapDispatchToProps = (dispatch, { subject }) => {
  const dispatchFetchUserBasics = (userId, timeframeId) =>
    dispatch(fetchUserBasics(userId, timeframeId));
  const dispatchFetchGroupBasics = (groupId, timeframeId) =>
    dispatch(fetchGroupBasics(groupId, timeframeId));
  const dispatchFetchCompanyBasics = (companyId, timeframeId) =>
    dispatch(fetchCompanyBasics(companyId, timeframeId));
  const dispatchChangeOkrOwner = (id, owner) =>
    dispatch(changeOkrOwner(subject, id, owner));
  const dispatchCopyOkr = (id, timeframeId) =>
    dispatch(copyOkr(subject, id, timeframeId));
  const dispatchDeleteOkr = id =>
    dispatch(deleteOkr(subject, id));
  return {
    dispatchFetchUserBasics,
    dispatchFetchGroupBasics,
    dispatchFetchCompanyBasics,
    dispatchChangeOkrOwner,
    dispatchCopyOkr,
    dispatchDeleteOkr,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OKRContainer);
