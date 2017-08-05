import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import InlineEditor, { inlineEditorPublicPropTypes } from './InlineEditor';

export default class InlineTextArea extends PureComponent {

  static propTypes = {
    maxLength: PropTypes.number,
    value: PropTypes.string,
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { maxLength, value = '', ...inlineEditorProps } = this.props;
    return (
      <InlineEditor
        fluid
        multiline
        {...{ value, ...inlineEditorProps }}
      >
        {({ setRef, currentValue, setValue }) =>
          <textarea
            ref={setRef}
            defaultValue={currentValue}
            {...{ maxLength }}
            onChange={e => setValue(e.target.value)}
          />}
      </InlineEditor>
    );
  }
}
