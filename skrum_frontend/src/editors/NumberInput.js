import React, { Component } from 'react';
import PropTypes from 'prop-types';
import NumberFormat from 'react-number-format';

export default class NumberInput extends Component {

  static propTypes = {
    min: PropTypes.number,
    max: PropTypes.number,
    mask: PropTypes.string,
    format: PropTypes.string,
    decimalPrecision: PropTypes.number,
    thousandSeparator: PropTypes.bool,
    placeholder: PropTypes.string,
    prefix: PropTypes.string,
    suffix: PropTypes.string,
    disabled: PropTypes.bool,
    readonly: PropTypes.bool,
    value: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    meta: PropTypes.shape({}), // available if under redux-form
  };

  render() {
    const { min, max, mask, format, decimalPrecision, thousandSeparator = true, placeholder,
      prefix, suffix, disabled, readonly, value, onChange, onFocus, onBlur,
      tabIndex } = this.props;
    return (
      <NumberFormat
        allowNegative={min === undefined || min < 0}
        isAllowed={({ value: val }) =>
          (min === undefined || val >= min) && (max === undefined || val <= max)}
        {...{ mask, format, decimalPrecision, thousandSeparator }}
        {...{ placeholder, prefix, suffix, disabled }}
        displayType={readonly ? 'text' : 'input'}
        {...{ value, onChange, onFocus, onBlur }}
        tabIndex={`${tabIndex}`}
        style={{ textAlign: 'right' }}
      />
    );
  }
}
