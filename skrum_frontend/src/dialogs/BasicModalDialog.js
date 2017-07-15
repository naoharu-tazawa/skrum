import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Gateway } from 'react-gateway';
import ReactModal2 from 'react-modal2';
import styles from './BasicModalDialog.css';

ReactModal2.getApplicationElement = () => document.getElementById('root');

export default class BasicModalDialog extends Component {

  static propTypes = {
    onClose: PropTypes.func,
    closeOnEsc: PropTypes.bool,
    closeOnBackdropClick: PropTypes.bool,
    children: PropTypes.node,
  };

  render() {
    const { onClose, closeOnEsc = true, closeOnBackdropClick = false, children } = this.props;
    return (
      <Gateway into="modal">
        <ReactModal2
          onClose={onClose}
          closeOnEsc={closeOnEsc}
          closeOnBackdropClick={closeOnBackdropClick}
          backdropClassName={styles.backdrop}
          modalClassName={styles.modal}
        >
          {children}
        </ReactModal2>
      </Gateway>
    );
  }
}
