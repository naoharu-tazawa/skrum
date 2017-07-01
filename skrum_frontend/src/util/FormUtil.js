import React from 'react';
import { connect } from 'react-redux';
import { reduxForm, Field } from 'redux-form';
import { isObject } from 'lodash';
import BasicModalDialog from '../dialogs/BasicModalDialog';

export const withReduxForm = (WrappedForm, form, reduxFormProps = {}) =>
  reduxForm({ form, ...reduxFormProps })(WrappedForm);

export const withLoadedReduxForm = (WrappedForm, form, stateToInitialValues, reduxFormProps = {}) =>
  connect(
    state => ({ initialValues: stateToInitialValues(state) }),
  )(withReduxForm(WrappedForm, form, reduxFormProps));

export const withBasicModalDialog = (WrappedForm, onClose, formProps = {}, dialogProps = {}) =>
  <BasicModalDialog {...{ onClose, ...dialogProps }}>
    <WrappedForm {...{ onClose, ...formProps }} />
  </BasicModalDialog>;

export const withReduxField = (WrappedComponent, name, fieldProps = {}) =>
  <Field
    component={({ input }) => <WrappedComponent {...input} {...fieldProps} />}
    name={name}
  />;

export const withItemisedReduxField = (WrappedComponent, name, fieldProps = {}) =>
  <Field
    component={({ input }) => <WrappedComponent {...input} {...fieldProps} />}
    name={name}
    // format={item => (item || {}).name}
    normalize={(value, previousValue) => (isObject(value) ? value : previousValue)}
  />;

export const withSelectReduxField = (WrappedComponent, name, fieldProps = {}) =>
  <Field
    component={({ input }) => <WrappedComponent {...input} {...fieldProps} />}
    name={name}
    normalize={value => (isObject(value) ? value.value : value)}
  />;
