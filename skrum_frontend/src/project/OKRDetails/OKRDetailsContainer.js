import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrPropTypes, ProgressSeriesPropTypes } from './propTypes';
import OkrDetails from './OkrDetails';
import KRDetailsList from './KRDetailsList/KRDetailsList';
import OkrProgressChart from './OkrProgressChart';
import NewOKR from '../OKR/NewOKR/NewOKR';
import { fetchOKRDetails, putOKR, changeKROwner, changeParentOkr, changeDisclosureType, setRatios, deleteKR } from './action';
import { syncOkr, syncParentOkr, syncRatios } from '../OKR/action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { mapOKR } from '../../util/OKRUtil';
import { withModal } from '../../util/ModalUtil';
import styles from './OKRDetailsContainer.css';

class OKRDetailsContainer extends Component {

  static propTypes = {
    subject: PropTypes.string.isRequired,
    isFetching: PropTypes.bool.isRequired,
    parentOkr: okrPropTypes,
    okr: okrPropTypes,
    progressSeries: ProgressSeriesPropTypes,
    dispatchFetchOKRDetails: PropTypes.func.isRequired,
    dispatchPutOKR: PropTypes.func.isRequired,
    dispatchChangeOkrOwner: PropTypes.func.isRequired, // passed from OKRContainer
    dispatchChangeKROwner: PropTypes.func.isRequired,
    dispatchChangeParentOkr: PropTypes.func.isRequired,
    dispatchChangeDisclosureType: PropTypes.func.isRequired,
    dispatchSetRatios: PropTypes.func.isRequired,
    dispatchCopyOkr: PropTypes.func.isRequired, // passed from OKRContainer
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
    const { isFetching, parentOkr, okr, progressSeries = [], subject,
      dispatchPutOKR, dispatchChangeKROwner, dispatchChangeParentOkr,
      dispatchCopyOkr, dispatchDeleteOkr, dispatchChangeOkrOwner,
      dispatchChangeDisclosureType, dispatchSetRatios, dispatchDeleteKR, openModal } = this.props;
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
                subject,
                dispatchPutOKR,
                dispatchChangeOkrOwner,
                dispatchChangeParentOkr,
                dispatchChangeDisclosureType,
                dispatchSetRatios,
                dispatchCopyOkr,
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
              subject,
              dispatchPutOKR,
              dispatchChangeKROwner,
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
  const { isFetching, parentOkr, objective, keyResults, chart } = okr;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const basicProps = { isFetching, pathname };
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
    dispatch(putOKR(id, data))
      .then(result => dispatch(syncOkr(subject, result)));
  const dispatchChangeKROwner = (id, owner) =>
    dispatch(changeKROwner(id, owner))
      .then(result => dispatch(syncOkr(subject, result)));
  const dispatchChangeParentOkr = (id, newParentOkrId) =>
    dispatch(changeParentOkr(id, newParentOkrId))
      .then(result => dispatch(syncParentOkr(subject, result)));
  const dispatchChangeDisclosureType = (id, disclosureType) =>
    dispatch(changeDisclosureType(id, disclosureType))
      .then(result => dispatch(syncOkr(subject, result)));
  const dispatchSetRatios = (id, ratios) =>
    dispatch(setRatios(id, ratios))
      .then(result => dispatch(syncRatios(subject, result)));
  const dispatchDeleteKR = id =>
    dispatch(deleteKR(id));
  return {
    dispatchFetchOKRDetails,
    dispatchPutOKR,
    dispatchChangeKROwner,
    dispatchChangeParentOkr,
    dispatchChangeDisclosureType,
    dispatchSetRatios,
    dispatchDeleteKR,
  };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(withModal(OKRDetailsContainer));
