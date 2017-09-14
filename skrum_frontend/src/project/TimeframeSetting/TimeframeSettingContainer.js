import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { fetchCompanyTimeframes } from './action';
import TimeframeListContainer from './TimeframeList/TimeframeListContainer';
import styles from './TimeframeSettingContainer.css';

class TimeframeSettingContainer extends Component {

  static propTypes = {
    companyId: PropTypes.number,
    isFetching: PropTypes.bool,
    dispatchFetchCompanyTimeframes: PropTypes.func,
  };

  componentWillMount() {
    const { dispatchFetchCompanyTimeframes, companyId } = this.props;
    dispatchFetchCompanyTimeframes(companyId);
  }

  render() {
    if (this.props.isFetching) {
      return <span className={styles.spinner} />;
    }
    return (
      <div className={styles.container}>
        <div className={styles.title}>目標期間設定</div>
        <div>
          <TimeframeListContainer />
        </div>
      </div>);
  }
}

const mapStateToProps = (state) => {
  const { companyId } = state.auth || {};
  const { isFetching } = state.timeframeSetting || {};
  return { companyId, isFetching };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchFetchCompanyTimeframes = companyId => dispatch(fetchCompanyTimeframes(companyId));
  return { dispatchFetchCompanyTimeframes };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(TimeframeSettingContainer);
