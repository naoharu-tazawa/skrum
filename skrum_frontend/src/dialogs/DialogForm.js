import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { errorType } from '../util/PropUtil';
import styles from './DialogForm.css';

export default class DialogForm extends Component {

  static propTypes = {
    title: PropTypes.string.isRequired,
    message: PropTypes.string,
    cancelButton: PropTypes.string,
    submitButton: PropTypes.string,
    submitEnabled: PropTypes.bool,
    handleSubmit: PropTypes.func, // for redux-form
    onSubmit: PropTypes.func.isRequired,
    onClose: PropTypes.func.isRequired,
    children: PropTypes.node,
    error: errorType,
  };

  render() {
    const { title, message, cancelButton = 'キャンセル', submitButton = 'OK', submitEnabled = true,
      handleSubmit, onSubmit, onClose, children } = this.props;
    return (
      <form
        className={styles.form}
        onSubmit={handleSubmit ? handleSubmit(onSubmit) : onSubmit}
      >
        <div className={styles.title}>{title}</div>
        {message && <div className={styles.message}>{message}</div>}
        <div className={styles.content}>{children}</div>
        <div className={styles.buttons}>
          <button
            className={styles.cancelButton}
            onClick={onClose}
          >
            {cancelButton}
          </button>
          <button
            type="submit"
            className={styles.submitButton}
            disabled={!submitEnabled}
          >
            {submitButton}
          </button>
        </div>
      </form>
    );
  }
}
