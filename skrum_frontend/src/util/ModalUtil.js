import React, { Component } from 'react';
import { isFunction } from 'lodash';
import BasicModalDialog from '../dialogs/BasicModalDialog';

export const withBasicModalDialog = (WrappedForm, onClose, formProps = {}, dialogProps = {}) =>
  <BasicModalDialog {...{ onClose, ...dialogProps }}>
    <WrappedForm {...{ onClose, ...formProps }} />
  </BasicModalDialog>;

export const withModal = (WrappedComponent, { wrapperClassName } = {}) => class extends Component {

  openModal = (modal, props) => this.setState({ activeModal:
    props || isFunction(modal) ? withBasicModalDialog(modal, this.closeActiveModal, props) :
      modal });

  closeActiveModal = () => this.setState({ activeModal: null });

  render = () => (
    <div className={wrapperClassName || 'modalWrapper'}>
      <WrappedComponent
        openModal={this.openModal}
        closeActiveModal={this.closeActiveModal}
        {...this.props}
      />
      {(this.state || {}).activeModal}
    </div>
  );
};
