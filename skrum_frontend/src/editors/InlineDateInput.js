import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import DatePickerInput from './DatePickerInput';
import InlineEditor, { inlineEditorPublicPropTypes } from './InlineEditor';
import { formatUtcDate, toUtcDate, isValidDate } from '../util/DatetimeUtil';

export default class InlineDateInput extends PureComponent {

  static propTypes = {
    value: PropTypes.string,
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { value = '', ...inlineEditorProps } = this.props;
    return (
      <InlineEditor
        dropdown
        formatter={formatUtcDate}
        {...{ value, ...inlineEditorProps }}
        style={{ minWidth: '10em' }}
      >
        {({ setRef, displayValue, setValue, submit }) =>
          <DatePickerInput
            ref={setRef}
            // containerClass={styles.datePicker}
            // wrapperClass={styles.datePickerWrapper}
            value={displayValue}
            onChange={({ target: { value: day } }) => isValidDate(day) && setValue(toUtcDate(day))}
            onKeyPress={e => e.key === 'Enter' && submit()}
          />}
      </InlineEditor>
    );
  }
}
