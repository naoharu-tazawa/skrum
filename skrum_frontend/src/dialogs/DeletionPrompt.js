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
    onClose: PropTypes.func.isRequired,
    children: PropTypes.node,
  };

  render() {
    const { title, prompt, onDelete, onClose, children } = this.props;
    const { yesSelected = false } = this.state || {};
    return (
      <BasicModalDialog onClose={onClose}>
        <DialogForm
          title={title}
          message={prompt}
          submitButton="削除"
          submitEnabled={yesSelected}
          onSubmit={onDelete}
          onClose={onClose}
        >
          <div className={styles.content}>{children}</div>
          <div className={styles.choice}>
            <label>
              <input
                type="radio"
                name="choice"
                value="no"
                defaultChecked
                onChange={() => this.setState({ yesSelected: false })}
              />
              いいえ
            </label>
            <label>
              <input
                type="radio"
                name="choice"
                value="yes"
                onChange={() => this.setState({ yesSelected: true })}
              />
              はい
            </label>
          </div>
        </DialogForm>
      </BasicModalDialog>
    );
  }
}
