import React, { Component } from 'react';
import PropTypes from 'prop-types';
// import DayPicker from 'react-day-picker/DayPicker';
import DayPickerInput from 'react-day-picker/DayPickerInput';
import localeUtils from 'react-day-picker/moment';
import 'moment/locale/ja';
import { formatDate, DateFormat } from '../util/DatetimeUtil';
import styles from './DatePickerInput.css';

export default class DatePickerInput extends Component {

  static propTypes = {
    containerClass: PropTypes.string,
    wrapperClass: PropTypes.string,
    overlayClass: PropTypes.string,
    value: PropTypes.oneOfType([
      PropTypes.string,
      PropTypes.shape({ target: PropTypes.shape({ value: PropTypes.string }) }),
    ]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    onDayChange: PropTypes.func,
    onDayClick: PropTypes.func,
    onKeyPress: PropTypes.func,
    // onClose: PropTypes.func.isRequired,
  };

  render() {
    const { containerClass, wrapperClass, overlayClass,
      value, onChange, onFocus, onBlur, onDayChange, onKeyPress } = this.props;
    const { target: { value: targetValue } = {} } = value || {};
    // const { hasOverlay = false } = this.state || {};
    const locale = 'ja';
    // return (
    //   <DayPicker
    //     locale={locale}
    //     localeUtils={localeUtils}
    //     renderDay={day => day.getDate().toLocaleString(locale)}
    //     classNames={styles}
    //   />
    // );
    return (
      <DayPickerInput
        classNames={{
          container: `${styles.inputContainer} ${containerClass}`,
          overlayWrapper: `${styles.overlayWrapper} ${wrapperClass}`,
          overlay: `${styles.overlay} ${overlayClass}`,
        }}
        format={DateFormat.YMD}
        // onClick={() => this.setState({ hasOverlay: true })}
        // onFocus={(e) => { this.setState({ hasOverlay: true }); onFocus(e); }}
        // onBlur={(e) => { this.setState({ hasOverlay: false }); onBlur(e); }}
        dayPickerProps={{
          classNames: styles,
          locale,
          localeUtils,
          // disabledDays: { daysOfWeek: [0] },
          renderDay: day => day.getDate().toLocaleString(locale),
          onDayClick: day => onChange({ target: { value: formatDate(day) } }),
          onDayChange,
          onKeyPress,
        }}
        {...{ value: targetValue || value, onChange, onFocus, onBlur }}
      />
    );
  }
}
