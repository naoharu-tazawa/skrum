import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import styles from './InlineText.css';

export default class InlineTextInput extends PureComponent {

  static propTypes = {
    // name: PropTypes.string.isRequired,
    type: PropTypes.string,
    value: PropTypes.string,
    readonly: PropTypes.bool,
    onSubmit: PropTypes.func,
  };

  state = {};

  componentWillReceiveProps() {
    this.setState({ value: undefined });
  }

  setEditingState(editing) {
    this.setState({ editing });
  }

  submitChange() {
    const { onSubmit } = this.props;
    const { value } = this.state;
    this.setEditingState(false);
    return value !== undefined && onSubmit && onSubmit(value);
  }

  cancelChange() {
    this.setEditingState(false);
    this.setState({ value: undefined });
  }

  render() {
    const { type = 'text', value = '', readonly = false } = this.props;
    const { editing = false } = this.state;
    return (
      <span
        className={`${styles.editor} ${readonly && styles.readonly} ${editing && styles.editing}`}
        onMouseDown={() => !readonly && this.setEditingState(true)}
      >
        {!editing && value}
        {!editing && !readonly && <span className={styles.editButton} />}
        {editing && (
          <input
            type={type}
            defaultValue={value}
            onChange={e => this.setState({ value: e.target.value })}
            onBlur={() => this.submitChange()}
            onKeyDown={e => e.key === 'Escape' && this.cancelChange()}
            onKeyPress={e => e.key === 'Enter' && this.submitChange()}
          />)}
        {editing && (
          <div className={styles.saveOptions}>
            <button className={styles.submit} onClick={() => this.submitChange()}>&nbsp;</button>
            <button className={styles.cancel} onClick={() => this.cancelChange()}>&nbsp;</button>
          </div>)}
      </span>
    );
  }
}
