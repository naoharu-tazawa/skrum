import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import FocusTrap from 'focus-trap-react';
import { isEmpty } from 'lodash';
import styles from './InlineEditor.css';

export const inlineEditorPublicPropTypes = {
  readonly: PropTypes.bool,
  required: PropTypes.bool,
  validate: PropTypes.func,
  onSubmit: PropTypes.func.isRequired,
  placeholder: PropTypes.string,
};

export default class InlineEditor extends PureComponent {

  static propTypes = {
    ...inlineEditorPublicPropTypes,
    value: PropTypes.oneOfType([PropTypes.string, PropTypes.shape({})]),
    fluid: PropTypes.bool,
    multiline: PropTypes.bool,
    dropdown: PropTypes.bool,
    formatter: PropTypes.func,
    preProcess: PropTypes.func,
    children: PropTypes.func,
    componentClassName: PropTypes.string,
    style: PropTypes.shape({}),
  };

  setEditing() {
    this.setState({ isEditing: true, error: undefined });
  }

  submit() {
    const { preProcess, value: defaultValue = '', required, validate, onSubmit } = this.props;
    const { value: stateValue } = this.state || {};
    const value = preProcess ? preProcess(stateValue) : stateValue;
    let unsetEditingCompanions = {};
    let unsetEditingCompletion = () => {};
    if (required && isEmpty(value === undefined ? defaultValue : value)) {
      unsetEditingCompletion = () => this.setState({ value, error: '入力してください' });
    } else if (value !== undefined && value !== defaultValue && onSubmit) {
      const validationError = validate && validate(value);
      if (validationError) {
        unsetEditingCompletion = () => this.setState({ value, error: validationError });
      } else {
        unsetEditingCompanions = { submitValue: value, error: undefined };
        unsetEditingCompletion = () =>
          onSubmit(value).then(({ error, payload: { message } = {} } = {}) =>
            this.setState({ value, submitValue: undefined, error: error && message }));
      }
    }
    this.setState({ isEditing: false, ...unsetEditingCompanions }, unsetEditingCompletion);
  }

  cancel() {
    this.setState({ isEditing: false, value: undefined });
  }

  render() {
    const { value: defaultValue = '', readonly = false, placeholder = '\u00A0', fluid, multiline,
      dropdown, formatter = v => v, children, componentClassName, style } = this.props;
    const { isEditing = false, value = defaultValue, submitValue, error } = this.state || {};
    const displayValue = formatter(submitValue !== undefined ? submitValue : value);
    return (
      <span
        className={`
          ${styles.editor}
          ${fluid && styles.fluid}
          ${multiline && styles.multiline}
          ${dropdown && styles.dropdown}
          ${readonly && styles.readonly}
          ${isEditing && styles.editing}
          ${submitValue !== undefined && !error && styles.submitting}
          ${error && styles.error}
          ${componentClassName || ''}
        `}
        style={style}
        onMouseUp={() => !readonly && submitValue === undefined && this.setEditing()}
        title={error}
      >
        <span className={styles.value}>
          {displayValue === '' ? <span className={styles.placeholder}>{placeholder}</span> : displayValue}
        </span>
        {!readonly && !isEditing &&
          <span
            className={styles.editButton}
            onMouseUp={() => submitValue === undefined && this.setEditing()}
          />}
        {isEditing && (
          <FocusTrap
            focusTrapOptions={{
              onActivate: () => this.input && this.input.select && this.input.select(),
              onDeactivate: this.cancel.bind(this),
              clickOutsideDeactivates: true,
            }}
            className={styles.inputArea}
          >
            {children({
              setRef: (ref) => { this.input = ref; },
              currentValue: value,
              displayValue,
              setValue: val => this.setState({ value: val }),
              submit: this.submit.bind(this),
            })}
            {isEditing && (
              <div className={styles.saveOptions}>
                <button className={styles.submit} onClick={this.submit.bind(this)}>&nbsp;</button>
                <button className={styles.cancel} onClick={this.cancel.bind(this)}>&nbsp;</button>
              </div>)}
          </FocusTrap>)}
      </span>
    );
  }
}
