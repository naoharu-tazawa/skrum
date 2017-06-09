import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import styles from './InlineText.css';

export default class InlineTextArea extends PureComponent {

  static propTypes = {
    // name: PropTypes.string.isRequired,
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
    const { value = '', readonly = false } = this.props;
    const { editing = false } = this.state;
    return (
      <span
        className={`${styles.editor} ${readonly && styles.readonly} ${editing && styles.editing}`}
        onMouseDown={() => !readonly && this.setEditingState(true)}
      >
        {!editing && value}
        {!editing && !readonly && <span className={styles.editButton} />}
        {editing && (
          <textarea
            defaultValue={value}
            onChange={e => this.setState({ value: e.target.value })}
            onBlur={() => this.submitChange()}
            onKeyDown={e => e.key === 'Escape' && this.cancelChange()}
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
