import React, { Component } from 'react';
import BasicModalDialog from '../dialogs/BasicModalDialog';

export const withBasicModalDialog = (WrappedForm, onClose, formProps = {}, dialogProps = {}) =>
  <BasicModalDialog {...{ onClose, ...dialogProps }}>
    <WrappedForm {...{ onClose, ...formProps }} />
  </BasicModalDialog>;

export const withModal = (WrappedComponent, { wrapperClassName } = {}) => class extends Component {

  openModal = (modal, props) => this.setState({
    active: withBasicModalDialog(modal, this.closeActive, props) });

  openModeless = (modeless, props) => this.setState({
    active: withBasicModalDialog(modeless, this.closeActive, props, { modeless: true }) });

  closeActive = () => this.setState({ active: null });

  render = () => (
    <div className={wrapperClassName || 'modalWrapper'}>
      <WrappedComponent
        openModal={this.openModal}
        openModeless={this.openModeless}
        closeActive={this.closeActive}
        {...this.props}
      />
      {(this.state || {}).active}
    </div>
  );
};
