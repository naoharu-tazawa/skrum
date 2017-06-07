import React, { Component } from 'react';
import { connect } from 'react-redux';
import { timeframesPropTypes } from './propTypes';
import TimeframeList from './TimeframeList';

class TimeframeListContainer extends Component {

  static propTypes = {
    items: timeframesPropTypes,
  };

  render() {
    return (
      <TimeframeList
        items={this.props.items}
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

export default connect(
  mapStateToProps,
)(TimeframeListContainer);
