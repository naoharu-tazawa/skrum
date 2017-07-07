import React, { Component } from 'react';
import PropTypes from 'prop-types';
import styles from './DialogForm.css';

export default class DialogForm extends Component {

  static propTypes = {
    title: PropTypes.string.isRequired,
    message: PropTypes.string,
    cancelButton: PropTypes.string,
    submitButton: PropTypes.string,
    isSubmitting: PropTypes.bool,
    handleSubmit: PropTypes.func, // for redux-form
    onSubmit: PropTypes.func.isRequired,
    onClose: PropTypes.func.isRequired,
    children: PropTypes.node,
    valid: PropTypes.bool,
    error: PropTypes.string,
  };

  render() {
    const { title, message, cancelButton = 'キャンセル', submitButton = 'OK',
      isSubmitting, handleSubmit, onSubmit, onClose, children, valid = true, error } = this.props;
    return (
      <form
        className={`${styles.form} ${isSubmitting ? styles.submitting : ''}`}
        onSubmit={handleSubmit ? handleSubmit(onSubmit) : onSubmit}
        disabled={isSubmitting}
      >
        <div className={styles.title}>{title}</div>
        {message && <div className={styles.message}>{message}</div>}
        <div className={styles.content}>
          {children}
          <div className={styles.error}>{error || <span>&nbsp;</span>}</div>
        </div>
        <div className={styles.buttons}>
          <button
            type="button"
            className={styles.cancelButton}
            onClick={onClose}
          >
            {cancelButton}
          </button>
          <button
            type="submit"
            className={styles.submitButton}
            disabled={!valid}
          >
            {submitButton}
          </button>
        </div>
      </form>
    );
  }
}
