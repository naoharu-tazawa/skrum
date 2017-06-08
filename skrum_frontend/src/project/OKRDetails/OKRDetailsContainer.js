import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrPropTypes, keyResultsPropTypes, ProgressSeriesPropTypes } from './propTypes';
import OKRDetails from './OKRDetails';
import KRDetailsList from './KRDetailsList/KRDetailsList';
import OKRObjectiveProgressChart from './OKRObjectiveProgressChart';
import { fetchOKRDetails } from './action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { mapOKR, mapKeyResult } from '../../util/OKRUtil';
import styles from './OKRDetailsContainer.css';

class OKRDetailsContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    error: PropTypes.shape({ message: PropTypes.string.isRequired }),
    okr: okrPropTypes,
    keyResults: keyResultsPropTypes,
    progressSeries: ProgressSeriesPropTypes,
    dispatchFetchOKRDetails: PropTypes.func,
    pathname: PropTypes.string,
  };

  componentWillMount() {
    const { pathname } = this.props;
    if (isPathFinal(pathname)) {
      this.fetchDetails(pathname);
    }
  }

  componentWillReceiveProps(next) {
    const { pathname } = next;
    if (this.props.pathname !== pathname) {
      this.fetchDetails(pathname);
    }
  }

  fetchDetails(pathname) {
    const { dispatchFetchOKRDetails } = this.props;
    const { aspect, aspectId } = explodePath(pathname);
    if (aspect === 'o') {
      dispatchFetchOKRDetails(aspectId);
    }
  }

  render() {
    const { isFetching, error, okr, keyResults = [], progressSeries = [] } = this.props;
    if (error) {
      return <div className={`${styles.container} ${styles.error}`} >{error.message}</div>;
    }
    if (isFetching || !okr) {
      return <div className={`${styles.container} ${styles.spinner}`} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.header}>
          <div className={styles.okr}>
            <div className={styles.sectionLabel}>Ｏの詳細</div>
            <OKRDetails okr={okr} />
          </div>
          <div className={styles.chart}>
            <div className={styles.sectionLabel}>Oの進捗状況</div>
            <OKRObjectiveProgressChart progressSeries={progressSeries} />
            <div style={{ clear: 'both' }} />
          </div>
        </div>
        <div className={styles.keyResults}>
          <div className={styles.sectionLabel}>上記Ｏに紐づくＫＲ</div>
          <KRDetailsList keyResults={keyResults} />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { okr } = state;
  const { isFetching, error, objective, keyResults, chart } = okr;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const basicProps = { isFetching, error, pathname };
  return !objective ? basicProps : {
    ...basicProps,
    okr: mapOKR(objective, []),
    keyResults: keyResults.map(mapKeyResult),
    progressSeries: chart,
  };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchOKRDetails = id =>
    dispatch(fetchOKRDetails(id));
  return {
    dispatchFetchOKRDetails,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OKRDetailsContainer);
