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
    timeframes: PropTypes.arrayOf(PropTypes.shape({
      timeframeId: PropTypes.number.isRequired,
      timeframeName: PropTypes.string.isRequired,
      defaultFlg: PropTypes.number,
    })),
    timeframeId: PropTypes.number,
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
  };

  render() {
    const { plain, timeframes, timeframeId, value: dirtyValue,
      onChange, onFocus, onBlur } = this.props;
    const timeframeOptions = orderBy(timeframes, 'timeframeId', 'asc')
      .map(({ timeframeId: value, timeframeName: label }) => ({ value, label }));
    const getTimeframeStyle = (id, currentId) =>
      `${styles.item} ${id === currentId ? styles.current : ''}`;
    const timeframeRenderer = ({ value: id, label }) =>
      <div className={getTimeframeStyle(id, timeframeId)}>
        {label}
      </div>;
    return (
      <Select
        className={`${styles.select} ${plain && styles.plain}`}
        options={timeframeOptions}
        optionRenderer={timeframeRenderer}
        value={dirtyValue || timeframeId}
        {...{ onChange, onFocus, onBlur }}
        placeholder=""
        clearable={false}
        searchable={false}
      />
    );
  }
}

const mapStateToProps = (state) => {
  const { timeframes = [] } = state.top.data || {};
  const { locationBeforeTransitions } = state.routing || {};
  const { pathname } = locationBeforeTransitions || {};
  const { timeframeId } = explodePath(pathname);
  return { timeframes, timeframeId };
};

export default connect(
  mapStateToProps,
)(TimeframesDropdown);
