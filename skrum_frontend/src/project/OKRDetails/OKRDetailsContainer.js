import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { okrPropTypes, keyResultsPropTypes } from '../OKR/OKRList/propTypes';
import OKRDetails from './OKRDetails';
import OKRList from '../OKR/OKRList/OKRList';
import { fetchOKRDetails } from './action';
import { explodePath, isPathFinal } from '../../util/RouteUtil';
import { mapOKR, mapKeyResult } from '../../util/OKRUtil';
import styles from './OKRDetailsContainer.css';

class OKRDetailsContainer extends Component {

  static propTypes = {
    isFetching: PropTypes.bool,
    details: okrPropTypes,
    keyResults: keyResultsPropTypes,
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
    const { subSection, subId } = explodePath(pathname);
    if (subSection === 'o') {
      dispatchFetchOKRDetails(subId);
    }
  }

  render() {
    const { details, keyResults = [] } = this.props;
    if (this.props.isFetching || !details) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.details}>
          <OKRDetails details={details} />
        </div>
        <div className={styles.keyResults}>
          <OKRList items={keyResults} />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { okr } = state;
  const { isFetching, objective, keyResults } = okr;
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const basicProps = { isFetching, pathname };
  return !objective ? basicProps : {
    ...basicProps,
    details: mapOKR(objective, []),
    keyResults: keyResults.map(mapKeyResult),
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
