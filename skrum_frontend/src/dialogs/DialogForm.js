import React, { Component } from 'react';
import PropTypes from 'prop-types';
import FocusTrap from 'focus-trap-react';
import { isFunction } from 'lodash';
import styles from './DialogForm.css';

export default class DialogForm extends Component {

  static propTypes = {
    title: PropTypes.string,
    message: PropTypes.string,
    compact: PropTypes.bool,
    constrainHeight: PropTypes.bool,
    cancelButton: PropTypes.string,
    submitButton: PropTypes.string,
    handleSubmit: PropTypes.func, // for redux-form
    onSubmit: PropTypes.func.isRequired,
    onClose: PropTypes.func,
    lastTabIndex: PropTypes.number,
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
    const { handleSubmit, onSubmit, onClose } = this.props;
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
        return onSubmit((this.state || {}).data)
          .then(({ error, payload: { message } = {} } = {}) => {
            if (!this.isUnmounting) {
              this.setState({ isSubmitting: false, submissionError: error && message });
              if (!error && onClose) onClose();
            }
          });
      };
  }

  render() {
    const { title, message, compact, constrainHeight,
      cancelButton = 'キャンセル', submitButton = 'OK',
      onClose, lastTabIndex, children, valid = true, error } = this.props;
    const { data = {}, isSubmitting = false, submissionError } = this.state || {};
    return (
      <form
        className={`${styles.form} ${isSubmitting ? styles.submitting : ''}`}
        onSubmit={this.submissionHandler()}
        disabled={isSubmitting}
      >
        {title && <div className={styles.title}>{title}</div>}
        {message && <div className={styles.message}>{message}</div>}
        <FocusTrap active={!compact}>
          <div className={`${styles.content} ${compact && styles.compact}`}>
            <div className={constrainHeight && styles.constrainedHeight}>
              {isFunction(children) ?
                children({ setFieldData: this.setFieldData.bind(this), data }) :
                children}
            </div>
            <div className={styles.error}>{error || submissionError || <span>&nbsp;</span>}</div>
          </div>
          <div className={`${styles.buttons} ${compact && styles.compact}`}>
            <div className={styles.filler} />
            {cancelButton && (
              <button
                type="button"
                className={styles.cancelButton}
                onClick={onClose}
                tabIndex={lastTabIndex && `${lastTabIndex + 1}`}
              >
                {cancelButton}
              </button>)}
            <button
              type="submit"
              className={styles.submitButton}
              disabled={isFunction(valid) ? !valid(data) : !valid}
              tabIndex={lastTabIndex && `${lastTabIndex + 2}`}
            >
              {submitButton}
            </button>
          </div>
        </FocusTrap>
      </form>
    );
  }
}
