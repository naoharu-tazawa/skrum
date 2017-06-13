import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { timeframesPropTypes } from './propTypes';
import { putTimeframe } from '../action';
import TimeframeList from './TimeframeList';

class TimeframeListContainer extends Component {

  static propTypes = {
    items: timeframesPropTypes,
    dispatchPutTimeframe: PropTypes.func.isRequired,
  };

  render() {
    const { items, dispatchPutTimeframe } = this.props;
    return (
      <TimeframeList
        items={items}
        dispatchPutTimeframe={dispatchPutTimeframe}
      />);
  }
}

const mapStateToProps = (state) => {
  const { data = [] } = state.timeframeSetting || {};
  const items = data.map((timeframe) => {
    const { timeframeId, timeframeName, startDate, endDate, defaultFlg } = timeframe;
    return {
      id: timeframeId,
      name: timeframeName,
      startDate,
      endDate,
      defaultFlg,
    };
  });
  return { items };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPutTimeframe = (id, data) =>
    dispatch(putTimeframe(id, data));
  return { dispatchPutTimeframe };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(TimeframeListContainer);
