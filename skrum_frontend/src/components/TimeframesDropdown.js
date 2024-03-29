import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { connect } from 'react-redux';
import Select from 'react-select';
import { orderBy } from 'lodash';
import { explodePath } from '../util/RouteUtil';
import styles from './TimeframesDropdown.css';

class TimeframesDropdown extends PureComponent {

  static propTypes = {
    plain: PropTypes.bool,
    excludeCurrent: PropTypes.bool,
    timeframes: PropTypes.arrayOf(PropTypes.shape({
      timeframeId: PropTypes.number.isRequired,
      timeframeName: PropTypes.string.isRequired,
      startDate: PropTypes.string.isRequired,
      endDate: PropTypes.string.isRequired,
      defaultFlg: PropTypes.number,
    })),
    timeframeId: PropTypes.number,
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
  };

  render() {
    const { plain, timeframes, timeframeId, value: dirtyValue,
      onChange, onFocus, onBlur, tabIndex } = this.props;
    const options = orderBy(timeframes, 'timeframeId', 'desc')
      .map(({ timeframeId: value, timeframeName: label }) => ({ value, label }));
    const getOptionStyle = (id, currentId) =>
      `${styles.item} ${id === currentId ? styles.current : ''}`;
    const optionRenderer = ({ value: id, label }) => (
      <div className={getOptionStyle(id, timeframeId)}>
        {label}
      </div>);
    return (
      <Select
        className={`${styles.select} ${plain && styles.plain}`}
        options={options}
        optionRenderer={optionRenderer}
        value={dirtyValue || timeframeId}
        {...{ onChange, onFocus, onBlur }}
        placeholder=""
        clearable={false}
        searchable={false}
        openOnFocus
        tabIndex={`${tabIndex}`}
      />
    );
  }
}

const mapStateToProps = (state, { excludeCurrent }) => {
  const { timeframes = [] } = state.top.data || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { timeframeId } = explodePath(pathname);
  return excludeCurrent ?
    { timeframes: timeframes.filter(tf => tf.timeframeId !== timeframeId) } :
    { timeframes, timeframeId };
};

export default connect(
  mapStateToProps,
)(TimeframesDropdown);
