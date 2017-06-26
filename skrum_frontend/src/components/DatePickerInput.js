import React, { Component } from 'react';
import PropTypes from 'prop-types';
// import DayPicker from 'react-day-picker/DayPicker';
import DayPickerInput from 'react-day-picker/DayPickerInput';
import localeUtils from 'react-day-picker/moment';
import 'moment/locale/ja';
import styles from './DatePickerInput.css';

export default class DatePickerInput extends Component {

  static propTypes = {
    value: PropTypes.string,
    onChange: PropTypes.func,
    onFocus: PropTypes.func,
    onBlur: PropTypes.func,
    // onClose: PropTypes.func.isRequired,
  };

  render() {
    const { value, onChange, onFocus, onBlur } = this.props;
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
          container: styles.inputContainer,
          overlayWrapper: styles.overlayWrapper,
          overlay: styles.overlay,
        }}
        dayPickerProps={{
          classNames: styles,
          locale,
          localeUtils,
          // disabledDays: { daysOfWeek: [0] },
          renderDay: day => day.getDate().toLocaleString(locale),
        }}
        {...{ value, onChange, onFocus, onBlur }}
      />
    );
  }
}
