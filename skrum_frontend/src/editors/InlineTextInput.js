import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import InlineEditor, { inlineEditorPublicPropTypes } from './InlineEditor';

export default class InlineTextInput extends PureComponent {

  static propTypes = {
    type: PropTypes.string,
    maxLength: PropTypes.number,
    value: PropTypes.string,
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { type = 'text', maxLength, value = '', ...inlineEditorProps } = this.props;
    return (
      <InlineEditor
        fluid
        {...{ value, ...inlineEditorProps }}
      >
        {({ setRef, setValue, submit }) =>
          <input
            ref={setRef}
            {...{ type, maxLength }}
            defaultValue={value}
            onChange={e => setValue(e.target.value)}
            onKeyPress={e => e.key === 'Enter' && submit()}
          />}
      </InlineEditor>
    );
  }
}
