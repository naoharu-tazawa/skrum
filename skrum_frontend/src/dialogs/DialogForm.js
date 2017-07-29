import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { isFunction } from 'lodash';
import styles from './DialogForm.css';

export default class DialogForm extends Component {

  static propTypes = {
    title: PropTypes.string,
    message: PropTypes.string,
    cancelButton: PropTypes.string,
    submitButton: PropTypes.string,
    handleSubmit: PropTypes.func, // for redux-form
    onSubmit: PropTypes.func.isRequired,
    onClose: PropTypes.func,
    children: PropTypes.oneOfType([PropTypes.node, PropTypes.func]),
    valid: PropTypes.oneOfType([PropTypes.bool, PropTypes.func]),
    error: PropTypes.string,
  };

  componentWillUnmount() {
    this.isUnmounting = true; // FIXME
  }

  setFieldData(data = {}) {
    const { data: oldData = {} } = this.state || {};
    this.setState({ data: { ...oldData, ...data } });
  }

  submissionHandler() {
    const { handleSubmit, onSubmit } = this.props;
    return handleSubmit ?
      handleSubmit((data) => {
        this.setState({ isSubmitting: true });
        try {
          return onSubmit(data).then(() =>
            !this.isUnmounting && this.setState({ isSubmitting: false }));
        } catch (e) {
          if (!this.isUnmounting) this.setState({ isSubmitting: false });
          throw e;
        }
      }) :
      (e) => {
        e.preventDefault();
        this.setState({ isSubmitting: true });
        return onSubmit((this.state || {}).data).then(() =>
          !this.isUnmounting && this.setState({ isSubmitting: false }));
      };
  }

  render() {
    const { title, message, cancelButton = 'キャンセル', submitButton = 'OK',
      onClose, children, valid = true, error } = this.props;
    const { data = {}, isSubmitting = false } = this.state || {};
    return (
      <form
        className={`${styles.form} ${isSubmitting ? styles.submitting : ''}`}
        onSubmit={this.submissionHandler()}
        disabled={isSubmitting}
      >
        {title && <div className={styles.title}>{title}</div>}
        {message && <div className={styles.message}>{message}</div>}
        <div className={styles.content}>
          {isFunction(children) ? children({ setFieldData: this.setFieldData.bind(this) }) :
            children}
          <div className={styles.error}>{error || <span>&nbsp;</span>}</div>
        </div>
        <div className={styles.buttons}>
          <div className={styles.filler} />
          {cancelButton && (
            <button
              type="button"
              className={styles.cancelButton}
              onClick={onClose}
            >
              {cancelButton}
            </button>)}
          <button
            type="submit"
            className={styles.submitButton}
            disabled={isFunction(valid) ? !valid(data) : !valid}
          >
            {submitButton}
          </button>
        </div>
      </form>
    );
  }
}
