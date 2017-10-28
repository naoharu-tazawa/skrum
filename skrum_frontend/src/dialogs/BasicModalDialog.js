import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Gateway } from 'react-gateway';
import ReactModal2 from 'react-modal2';
import styles from './BasicModalDialog.css';

ReactModal2.getApplicationElement = () => document.getElementById('root');

export default class BasicModalDialog extends Component {

  static propTypes = {
    modeless: PropTypes.bool,
    onClose: PropTypes.func,
    closeOnEsc: PropTypes.bool,
    closeOnBackdropClick: PropTypes.bool,
    children: PropTypes.node,
  };

  render() {
    const { modeless, onClose, closeOnEsc = true, closeOnBackdropClick, children } = this.props;
    if (modeless) {
      return (
        <Gateway into="global">
          {children}
        </Gateway>
      );
    }
    return (
      <Gateway into="global">
        <ReactModal2
          onClose={onClose}
          closeOnEsc={closeOnEsc}
          closeOnBackdropClick={!!closeOnBackdropClick}
          backdropClassName={styles.backdrop}
          modalClassName={styles.modal}
        >
          {children}
        </ReactModal2>
      </Gateway>
    );
  }
}
