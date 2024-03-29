import React, { Component } from 'react';
import PropTypes from 'prop-types';
import FocusTrap from 'focus-trap-react';
import { isFunction } from 'lodash';
import styles from './DialogForm.css';

export default class DialogForm extends Component {

  static propTypes = {
    title: PropTypes.string,
    message: PropTypes.string,
    plain: PropTypes.bool,
    compact: PropTypes.bool,
    modeless: PropTypes.bool,
    constrainedHeight: PropTypes.bool,
    fullHeight: PropTypes.bool,
    footerContent: PropTypes.node,
    cancelButton: PropTypes.string,
    submitButton: PropTypes.string,
    auxiliaryButton: PropTypes.func,
    handleSubmit: PropTypes.func, // for redux-form
    onSubmit: PropTypes.func.isRequired,
    onClose: PropTypes.func,
    lastTabIndex: PropTypes.number,
    children: PropTypes.oneOfType([PropTypes.node, PropTypes.func]),
    valid: PropTypes.oneOfType([PropTypes.bool, PropTypes.func]),
    error: PropTypes.string,
    className: PropTypes.string,
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
          return onSubmit(data).then((({ error, payload: { message } = {} } = {}) =>
            !this.isUnmounting && this.setState({
              isSubmitting: false, submissionError: error && message })));
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
    const { title, message, plain, compact, modeless, constrainedHeight, fullHeight,
      footerContent, cancelButton = 'キャンセル', submitButton = 'OK', auxiliaryButton,
      onClose, lastTabIndex, children, valid = true, error, className = '' } = this.props;
    const { data = {}, isSubmitting = false, submissionError } = this.state || {};
    return (
      <form
        className={`${styles.form} ${className} ${plain && styles.plain} ${isSubmitting ? styles.submitting : ''}`}
        onSubmit={this.submissionHandler()}
        disabled={isSubmitting}
      >
        {title && <div className={styles.title}>{title}</div>}
        {message && <div className={styles.message}>{message}</div>}
        <FocusTrap
          active={!modeless}
          style={{ display: 'flex', flexDirection: 'column', height: '100%' }}
        >
          <div className={`${styles.content} ${compact && styles.compact} ${fullHeight && styles.fullHeight}`}>
            <div className={`${constrainedHeight && styles.constrainedHeight} ${fullHeight && styles.fullHeight}`}>
              {isFunction(children) ?
                children({ setFieldData: this.setFieldData.bind(this), data }) :
                children}
            </div>
            {(!compact || error || submissionError) && (
              <section className={styles.error}>
                {error || submissionError || <span>&nbsp;</span>}
              </section>)}
          </div>
          <div className={`${styles.buttons} ${compact && styles.compact}`}>
            <div className={styles.filler}>{footerContent}</div>
            {cancelButton && (
              <button
                type="button"
                className={styles.cancelButton}
                onClick={onClose}
                tabIndex={lastTabIndex && `${lastTabIndex + 1}`}
              >
                {cancelButton}
              </button>)}
            {auxiliaryButton && auxiliaryButton(
              { className: styles.cancelButton, tabIndex: lastTabIndex + 2 })}
            <button
              type="submit"
              className={styles.submitButton}
              disabled={isFunction(valid) ? !valid(data) : !valid}
              tabIndex={lastTabIndex && `${lastTabIndex + 3}`}
            >
              {submitButton}
            </button>
          </div>
        </FocusTrap>
      </form>
    );
  }
}
