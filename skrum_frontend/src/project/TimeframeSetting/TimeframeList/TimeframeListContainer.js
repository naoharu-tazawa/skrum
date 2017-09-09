import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import { timeframesPropTypes } from './propTypes';
import { putTimeframe, defaultTimeframe, deleteTimeframe } from '../action';
import TimeframeList from './TimeframeList';

class TimeframeListContainer extends Component {

  static propTypes = {
    items: timeframesPropTypes,
    dispatchPutTimeframe: PropTypes.func.isRequired,
    dispatchDefaultTimeframe: PropTypes.func.isRequired,
    dispatchDeleteTimeframe: PropTypes.func.isRequired,
  };

  render() {
    const { items, dispatchPutTimeframe, dispatchDefaultTimeframe, dispatchDeleteTimeframe } =
      this.props;
    return (
      <TimeframeList
        items={items}
        {...{ dispatchPutTimeframe, dispatchDefaultTimeframe, dispatchDeleteTimeframe }}
      />);
  }
}

const mapStateToProps = (state) => {
  const { data = [] } = state.timeframeSetting || {};
  const items = data.map(({ timeframeId, timeframeName, startDate, endDate, defaultFlg }) => ({
    id: timeframeId,
    name: timeframeName,
    startDate,
    endDate,
    defaultFlg,
  }));
  return { items };
};

const mapDispatchToProps = (dispatch) => {
  const dispatchPutTimeframe = (id, data) =>
    dispatch(putTimeframe(id, data));
  const dispatchDefaultTimeframe = id =>
    dispatch(defaultTimeframe(id));
  const dispatchDeleteTimeframe = id =>
    dispatch(deleteTimeframe(id));
  return { dispatchPutTimeframe, dispatchDefaultTimeframe, dispatchDeleteTimeframe };
};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(TimeframeListContainer);
