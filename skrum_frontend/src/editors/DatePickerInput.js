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
    align: PropTypes.oneOf(['left', 'center', 'right']),
    value: PropTypes.oneOfType([
      PropTypes.string,
      PropTypes.shape({ target: PropTypes.shape({ value: PropTypes.string }) }),
    ]),
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    tabIndex: PropTypes.oneOfType([PropTypes.number, PropTypes.string]),
    onDayChange: PropTypes.func,
    onDayClick: PropTypes.func,
    onKeyPress: PropTypes.func,
    // onClose: PropTypes.func.isRequired,
    meta: PropTypes.shape({}), // available if under redux-form
  };

  render() {
    const { containerClass, wrapperClass, overlayClass, align = 'left',
      value, onChange, onFocus, onBlur, tabIndex, onDayChange, onKeyPress, meta } = this.props;
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
    const overlay = `${styles.overlay} ${align === 'center' ? styles.center : ''} ${align === 'right' ? styles.right : ''}`;
    return (
      <DayPickerInput
        classNames={{
          container: `${styles.inputContainer} ${containerClass}`,
          overlayWrapper: `${styles.overlayWrapper} ${wrapperClass}`,
          overlay: `${overlay} ${overlayClass}`,
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
          onDayClick: day => onChange(meta ? formatDate(day) :
            { target: { value: formatDate(day) } }),
          onDayChange,
          onKeyPress,
        }}
        {...{ value: targetValue || value, onChange, onFocus, onBlur }}
        tabIndex={`${tabIndex}`}
      />
    );
  }
}
