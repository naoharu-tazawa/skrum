import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import FocusTrap from 'focus-trap-react';
import styles from './InlineEditors.css';

export default class InlineTextInput extends PureComponent {

  static propTypes = {
    type: PropTypes.string,
    maxLength: PropTypes.number,
    value: PropTypes.string,
    placeholder: PropTypes.string,
    readonly: PropTypes.bool,
    required: PropTypes.bool,
    validate: PropTypes.func,
    onSubmit: PropTypes.func,
  };

  setEditing() {
    this.setState({ isEditing: true, error: undefined });
  }

  submit() {
    const { value: defaultValue = '', required, validate, onSubmit } = this.props;
    const { value } = this.state || {};
    let unsetEditingCompanions = {};
    let unsetEditingCompletion = () => {};
    if (required && value === '') {
      unsetEditingCompletion = () => this.setState({ value, error: '入力してください' });
    } else if (value !== undefined && value !== defaultValue && onSubmit) {
      const validationError = validate && validate(value);
      if (validationError) {
        unsetEditingCompletion = () => this.setState({ value, error: validationError });
      } else {
        unsetEditingCompanions = { submitValue: value, error: undefined };
        unsetEditingCompletion = () => onSubmit(value, ({ error, payload: { message } }) =>
          this.setState({ value, submitValue: undefined, error: error && message }));
      }
    }
    this.setState({ isEditing: false, ...unsetEditingCompanions }, unsetEditingCompletion);
  }

  cancel() {
    this.setState({ isEditing: false, value: undefined });
  }

  render() {
    const { type = 'text', value: defaultValue = '', placeholder = '\u00A0', readonly = false, maxLength } = this.props;
    const { isEditing = false, value = defaultValue, submitValue, error } = this.state || {};
    const displayValue = submitValue !== undefined ? submitValue : value;
    return (
      <span
        className={`
          ${styles.editor}
          ${styles.fluid}
          ${readonly && styles.readonly}
          ${isEditing && styles.editing}
          ${submitValue !== undefined && !error && styles.submitting}
          ${error && styles.error}
        `}
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
              onActivate: () => this.input.select(),
              onDeactivate: this.cancel.bind(this),
              clickOutsideDeactivates: true,
            }}
            className={styles.inputArea}
          >
            <input
              ref={(ref) => { this.input = ref; }}
              type={type}
              defaultValue={displayValue}
              {...{ maxLength }}
              onChange={e => this.setState({ value: e.target.value })}
              onKeyPress={e => e.key === 'Enter' && this.submit()}
            />
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
