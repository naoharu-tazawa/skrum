import React, { Component } from 'react';
import PropTypes from 'prop-types';
import BasicModalDialog from './BasicModalDialog';
import DialogForm from './DialogForm';
import styles from './ConfirmationPrompt.css';

export default class ConfirmationPrompt extends Component {

  static propTypes = {
    title: PropTypes.string.isRequired,
    prompt: PropTypes.string.isRequired,
    confirmButton: PropTypes.string.isRequired,
    onConfirm: PropTypes.func.isRequired,
    onClose: PropTypes.func.isRequired,
    children: PropTypes.node.isRequired,
    warning: PropTypes.node,
  };

  render() {
    const { title, prompt, confirmButton, onConfirm, onClose, children, warning } = this.props;
    const { yesSelected = false } = this.state || {};
    return (
      <BasicModalDialog onClose={onClose}>
        <DialogForm
          title={title}
          message={prompt}
          submitButton={confirmButton}
          valid={yesSelected}
          onSubmit={onConfirm}
          onClose={onClose}
        >
          <div className={styles.content}>{children}</div>
          <div className={styles.warning}>{warning}</div>
          <div className={styles.choice}>
            <label>
              <input
                name="choice"
                type="radio"
                value="no"
                defaultChecked
                onClick={() => this.setState({ yesSelected: false })}
              />
              いいえ
            </label>
            <label>
              <input
                name="choice"
                type="radio"
                value="yes"
                onClick={() => this.setState({ yesSelected: true })}
              />
              はい
            </label>
          </div>
        </DialogForm>
      </BasicModalDialog>
    );
  }
}
