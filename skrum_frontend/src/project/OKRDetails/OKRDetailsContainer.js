import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrPropTypes, keyResultsPropTypes, ProgressSeriesPropTypes } from './propTypes';
import OKRDetails from './OKRDetails';
import KRDetailsList from './KRDetailsList/KRDetailsList';
import OKRObjectiveProgressChart from './OKRObjectiveProgressChart';
import { fetchOKRDetails, putOKR } from './action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { mapOKR, mapKeyResult } from '../../util/OKRUtil';
import styles from './OKRDetailsContainer.css';

class OKRDetailsContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    error: PropTypes.shape({ message: PropTypes.string.isRequired }),
    parentOkr: okrPropTypes,
    okr: okrPropTypes,
    keyResults: keyResultsPropTypes,
    progressSeries: ProgressSeriesPropTypes,
    dispatchFetchOKRDetails: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    pathname: PropTypes.string.isRequired,
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
    const { isFetching, error, parentOkr, okr, keyResults = [], progressSeries = [],
      dispatchPutOKR } = this.props;
    if (error) {
      return <div className={`${styles.container} ${styles.error}`} >{error.message}</div>;
    }
    if (isFetching || !okr) {
      return <div className={`${styles.container} ${styles.spinner}`} />;
    }
    return (
      <div className={styles.container}>
        <section className={`${styles.overall_info} ${styles.cf}`}>
          <div className={`${styles.basic_info} ${styles.h_line} ${styles.floatL}`}>
            <div className={styles.ttl}><h2>目標の詳細</h2></div>
            <OKRDetails parentOkr={parentOkr} okr={okr} dispatchPutOKR={dispatchPutOKR} />
          </div>
          <div className={`${styles.overall_situation} ${styles.h_line} ${styles.floatR}`}>
            <div className={styles.ttl}><h2>目標の進捗状況</h2></div>
            <OKRObjectiveProgressChart progressSeries={progressSeries} />
          </div>
        </section>
        <section className={styles.list}>
          <div className={styles.ttl_list}><h2>上記目標に紐づくサブ目標</h2></div>
          <KRDetailsList keyResults={keyResults} dispatchPutOKR={dispatchPutOKR} />
        </section>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { okr } = state;
  const { isFetching, error, parentOkr, objective, keyResults, chart } = okr;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const basicProps = { isFetching, error, pathname };
  return !objective ? basicProps : {
    ...basicProps,
    parentOkr: mapOKR(parentOkr, []),
    okr: mapOKR(objective, []),
    keyResults: keyResults.map(mapKeyResult),
    progressSeries: chart,
  };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchOKRDetails = id =>
    dispatch(fetchOKRDetails(id));
  const dispatchPutOKR = (id, data) =>
    dispatch(putOKR(id, data));
  return {
    dispatchFetchOKRDetails,
    dispatchPutOKR,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(OKRDetailsContainer);
