import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { Link } from 'react-router';
import _ from 'lodash';
import UserInfoContainer from './UserInfo/UserInfoContainer';
import GroupInfoContainer from './GroupInfo/GroupInfoContainer';
import CompanyInfoContainer from './CompanyInfo/CompanyInfoContainer';
import { UserOKROverallChartsContainer, GroupOKROverallChartsContainer, CompanyOKROverallChartsContainer } from './OKRList/OKROverallChartsContainer';
import { UserOKRListContainer, GroupOKRListContainer, CompanyOKRListContainer } from './OKRList/OKRListContainer';
import { UserOKRAlignmentsInfoContainer, GroupOKRAlignmentsInfoContainer } from './OKRAlignmentsInfo/OKRAlignmentsInfoContainer';
import OKRDetailsContainer from './OKRDetails/OKRDetailsContainer';
import { fetchUserBasics, fetchGroupBasics, fetchCompanyBasics } from './action';
import { explodePath, implodePath, comparePath, isPathFinal } from '../../util/RouteUtil';
import styles from './OKRContainer.css';

class OKRContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    subject: PropTypes.string,
    dispatchFetchUserBasics: PropTypes.func,
    dispatchFetchGroupBasics: PropTypes.func,
    dispatchFetchCompanyBasics: PropTypes.func,
    pathname: PropTypes.string,
    okrIds: PropTypes.arrayOf(PropTypes.number),
  };

  state = {
    showAlignmentsInfo: false,
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
    const { isFetching, pathname, okrIds } = this.props;
    if (isFetching) {
      return <div className={styles.spinner} />;
    }
    const { showAlignmentsInfo } = this.state;
    const { aspect, aspectId: okrId, ...basicPath } = explodePath(pathname);
    const { subject } = basicPath;
    const okrIndex = okrId ? okrIds.indexOf(_.toNumber(okrId)) : null;
    const prevOkrId = okrIndex > 0 ? okrIds[okrIndex - 1] : null;
    const nextOkrId = okrIndex < okrIds.length - 1 ? okrIds[okrIndex + 1] : null;
    const showDetails = aspect === 'o' && okrId;
    const chartRadius = 60;
    return (
      <div className={styles.container}>
        <div style={!showDetails ? { display: 'none' } : {}}>
          <div className={styles.navigator}>
            <Link to={implodePath(basicPath)} className={styles.backLink}>所有OKR一覧へ戻る</Link>
            <Link
              to={nextOkrId ? implodePath({ aspect: 'o', aspectId: nextOkrId, ...basicPath }) : ''}
              className={`${styles.naviLink} ${styles.nextLink} ${!nextOkrId ? styles.disabled : ''}`}
            >
              次のOKR
            </Link>
            <Link
              to={prevOkrId ? implodePath({ aspect: 'o', aspectId: prevOkrId, ...basicPath }) : ''}
              className={`${styles.naviLink} ${styles.prevLink} ${!prevOkrId ? styles.disabled : ''}`}
            >
              前のOKR
            </Link>
          </div>
          <OKRDetailsContainer />
        </div>
        <div style={showDetails ? { display: 'none' } : {}}>
          <div className={styles.header}>
            <div className={styles.info}>
              <div className={styles.sectionLabel}>基本情報</div>
              {this.renderInfoContainer()}
            </div>
            <div className={styles.overall} style={{ minWidth: (chartRadius * 4) + 80 + 14 }}>
              <div className={styles.sectionLabel}>全体の状況</div>
              {this.renderChartContainer()}
            </div>
          </div>
          <div className={styles.okrList}>
            <div className={styles.sectionLabel}>
              <Link
                className={showAlignmentsInfo || subject === 'company' ? styles.tabNormal : styles.tabSelected}
                onClick={() => this.setState({ showAlignmentsInfo: false })}
                to={pathname}
              >
                所有OKR一覧
              </Link>
              {subject === 'company' ? null : (
                <Link
                  className={showAlignmentsInfo ? styles.tabSelected : styles.tabNormal}
                  onClick={() => this.setState({ showAlignmentsInfo: true })}
                  to={pathname}
                >
                  目標の紐付け先
                </Link>)}
            </div>
            <div style={showAlignmentsInfo ? { display: 'none' } : {}}>
              {this.renderOKRListContainer()}
            </div>
            <div style={showAlignmentsInfo ? {} : { display: 'none' }}>
              {this.renderOKRAlignmentsInfoContainer()}
            </div>
          </div>
        </div>
      </div>
    );
  }
}

const mapStateToProps = (state) => {
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { subject } = explodePath(pathname);
  const { isFetching = false, [subject]: basics = {} } = state.basics || {};
  const { okrs = [] } = basics || {};
  const okrIds = okrs.map(({ okrId }) => okrId);
  return { isFetching, pathname, okrIds };
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
