import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import styles from './TimeframeContainer.css';
import { fetchCompanyTimeframes } from './action';
import TimeframeListContainer from './TimeframeList/TimeframeListContainer';

class TimeframeContainer extends Component {

  static propTypes = {
    companyId: PropTypes.number,
    isFetching: PropTypes.bool,
    dispatchFetchCompanyTimeframes: PropTypes.func,
  };

  componentWillMount() {
    const { dispatchFetchCompanyTimeframes, companyId } = this.props;
    dispatchFetchCompanyTimeframes(companyId);
  }

  componentWillReceiveProps() {
  }

  render() {
    if (this.props.isFetching) {
      return <div className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.title}>タイムフレーム設定</div>
        <div>
          <TimeframeListContainer />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth || {};
  const { isFetching = false } = state.timeframeSetting || {};
  return { companyId, isFetching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchCompanyTimeframes = companyId => dispatch(fetchCompanyTimeframes(companyId));
  return { dispatchFetchCompanyTimeframes };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(TimeframeContainer);
