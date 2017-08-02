import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrPropTypes, ProgressSeriesPropTypes } from './propTypes';
import OkrDetails from './OkrDetails';
import KRDetailsList from './KRDetailsList/KRDetailsList';
import OkrProgressChart from './OkrProgressChart';
import NewOKR from '../OKR/NewOKR/NewOKR';
import { fetchOKRDetails, putOKR, changeOwner, changeParentOkr, changeDisclosureType, deleteKR } from './action';
import { syncOkr } from '../OKR/action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { mapOKR } from '../../util/OKRUtil';
import { withModal } from '../../util/ModalUtil';
import styles from './OKRDetailsContainer.css';

class OKRDetailsContainer extends Component {

  static propTypes = {
    subject: PropTypes.string,
    isFetching: PropTypes.bool,
    error: PropTypes.shape({ message: PropTypes.string.isRequired }),
    parentOkr: okrPropTypes,
    okr: okrPropTypes,
    progressSeries: ProgressSeriesPropTypes,
    dispatchFetchOKRDetails: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeOwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchDeleteOkr: PropTypes.func.isRequired, // passed from OKRContainer
    dispatchDeleteKR: PropTypes.func.isRequired,
    openModal: PropTypes.func.isRequired,
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
    const { isFetching, /* error, */ parentOkr, okr, progressSeries = [],
      dispatchPutOKR, dispatchChangeOwner, dispatchChangeParentOkr, dispatchDeleteOkr,
      dispatchChangeDisclosureType, dispatchDeleteKR, openModal } = this.props;
    // TODO use toastr
    // if (error) {
    //   return <div className={`${styles.container} ${styles.error}`} >{error.message}</div>;
    // }
    if (isFetching || !okr) {
      return null; // <div className={`${styles.container} ${styles.spinner}`} />;
    }
    return (
      <div className={styles.container}>
        <section className={`${styles.overall_info} ${styles.cf}`}>
          <div className={`${styles.basic_info} ${styles.h_line} ${styles.floatL}`}>
            <div className={styles.ttl}><h2>目標の詳細</h2></div>
            <OkrDetails
              {...{
                parentOkr,
                okr,
                dispatchPutOKR,
                dispatchChangeOwner,
                dispatchChangeParentOkr,
                dispatchChangeDisclosureType,
                dispatchDeleteOkr,
              }}
            />
          </div>
          <div className={`${styles.overall_situation} ${styles.h_line} ${styles.floatR}`}>
            <div className={styles.ttl}><h2>目標の進捗状況</h2></div>
            <OkrProgressChart progressSeries={progressSeries} />
          </div>
        </section>
        <section className={styles.list}>
          <div className={styles.ttl_list}><h2>上記目標に紐づくサブ目標</h2></div>
          <KRDetailsList
            parentOkr={okr}
            keyResults={okr.keyResults}
            onAdd={() => openModal(NewOKR, { type: 'KR', parentOkr: okr })}
            {...{
              dispatchPutOKR,
              dispatchChangeOwner,
              dispatchChangeParentOkr,
              dispatchChangeDisclosureType,
              dispatchDeleteKR,
            }}
          />
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
    parentOkr: parentOkr === null ? undefined : mapOKR(parentOkr, []),
    okr: mapOKR(objective, keyResults),
    progressSeries: chart,
  };
};

const mapDispatchToProps = (dispatch, { subject }) => {
  const dispatchFetchOKRDetails = id =>
    dispatch(fetchOKRDetails(id));
  const dispatchPutOKR = (id, data) =>
    dispatch(putOKR(id, data)).then(result => dispatch(syncOkr(subject, result)));
  const dispatchChangeOwner = (id, owner) =>
    dispatch(changeOwner(id, owner));
  const dispatchChangeParentOkr = (id, newParentOkrId) =>
    dispatch(changeParentOkr(id, newParentOkrId));
  const dispatchChangeDisclosureType = (id, disclosureType) =>
    dispatch(changeDisclosureType(id, disclosureType));
  const dispatchDeleteKR = id =>
    dispatch(deleteKR(id));
  return {
    dispatchFetchOKRDetails,
    dispatchPutOKR,
    dispatchChangeOwner,
    dispatchChangeParentOkr,
    dispatchChangeDisclosureType,
    dispatchDeleteKR,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(withModal(OKRDetailsContainer));
