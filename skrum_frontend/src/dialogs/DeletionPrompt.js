import React, { Component } from 'react';
import PropTypes from 'prop-types';
import BasicModalDialog from './BasicModalDialog';
import DialogForm from './DialogForm';
import styles from './DeletionPrompt.css';

export default class DeletionPrompt extends Component {

  static propTypes = {
    title: PropTypes.string.isRequired,
    prompt: PropTypes.string.isRequired,
    onDelete: PropTypes.func.isRequired,
    isDeleting: PropTypes.bool.isRequired,
    onClose: PropTypes.func.isRequired,
    children: PropTypes.node.isRequired,
  };

  render() {
    const { title, prompt, onDelete, isDeleting, onClose, children } = this.props;
    const { yesSelected = false } = this.state || {};
    return (
      <BasicModalDialog onClose={onClose}>
        <DialogForm
          title={title}
          message={prompt}
          submitButton="削除"
          valid={yesSelected}
          onSubmit={(e) => { e.preventDefault(); onDelete(); }}
          isSubmitting={isDeleting}
          onClose={onClose}
        >
          <div className={styles.content}>{children}</div>
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
