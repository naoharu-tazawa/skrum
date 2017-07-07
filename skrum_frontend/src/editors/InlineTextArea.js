import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import FocusTrap from 'focus-trap-react';
import styles from './InlineText.css';

export default class InlineTextArea extends PureComponent {

  static propTypes = {
    // name: PropTypes.string.isRequired,
    value: PropTypes.string,
    readonly: PropTypes.bool,
    maxLength: PropTypes.number,
    onSubmit: PropTypes.func,
  };

  componentWillReceiveProps() {
    // this.setState({ value: undefined });
  }

  setEditing() {
    this.setState({ isEditing: true, submitValue: undefined, submitError: undefined });
  }

  submit() {
    const { onSubmit, value: defaultValue = '' } = this.props;
    const { value } = this.state || {};
    if (value !== undefined && value !== defaultValue && onSubmit) {
      this.setState({ isEditing: false, submitValue: value, submitError: undefined }, () =>
        onSubmit(value, ({ error, payload }) =>
          this.setState({ submitValue: error ? value : undefined, submitError: payload.message })));
    } else {
      this.setState({ isEditing: false });
    }
  }

  cancel() {
    this.setState({ isEditing: false, value: undefined });
  }

  render() {
    const { value = '', readonly = false, maxLength } = this.props;
    const { isEditing = false, submitValue, submitError } = this.state || {};
    const displayValue = submitValue !== undefined ? submitValue : value;
    return (
      <span
        className={`
          ${styles.editor}
          ${styles.multiline}
          ${readonly && styles.readonly}
          ${isEditing && styles.editing}
          ${submitValue !== undefined && !submitError && styles.submitting}
          ${submitError && styles.error}
        `}
        onMouseUp={() => !readonly && !submitValue && this.setEditing()}
        title={submitError}
      >
        <span className={styles.value}>
          {displayValue === '' ? <span>&nbsp;</span> : displayValue}
        </span>
        {!readonly && !isEditing &&
          <span
            className={styles.editButton}
            onMouseUp={() => !submitValue && this.setEditing()}
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
            <textarea
              ref={(ref) => { this.input = ref; }}
              defaultValue={value}
              {...{ maxLength }}
              onChange={e => this.setState({ value: e.target.value })}
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
