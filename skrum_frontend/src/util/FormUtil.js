import React from 'react';
import { connect } from 'react-redux';
import { reduxForm, Field } from 'redux-form';
import { isObject, toNumber } from 'lodash';

export const withReduxForm = (WrappedForm, form, reduxFormProps = {}) =>
  reduxForm({ form, ...reduxFormProps })(WrappedForm);

export const withLoadedReduxForm = (WrappedForm, form, stateToInitialValues, reduxFormProps = {}) =>
  connect(
    state => ({ initialValues: stateToInitialValues ? stateToInitialValues(state) : {} }),
  )(withReduxForm(WrappedForm, form, reduxFormProps));

export const withReduxField = (WrappedComponent, name, props, fieldProps) =>
  <Field
    component={({ input, meta }) => <WrappedComponent {...{ ...input, meta }} {...props} />}
    name={name}
    {...fieldProps}
  />;

export const withItemisedReduxField = (WrappedComponent, name, props, fieldProps) =>
  <Field
    component={({ input, meta }) => <WrappedComponent {...{ ...input, meta }} {...props} />}
    name={name}
    // format={item => (item || {}).name}
    normalize={(value, previousValue) => (isObject(value) ? value : previousValue)}
    {...fieldProps}
  />;

export const withSelectReduxField = (WrappedComponent, name, props, fieldProps) =>
  <Field
    component={({ input, meta }) => <WrappedComponent {...{ ...input, meta }} {...props} />}
    name={name}
    normalize={value => (isObject(value) ? value.value : value)}
    {...fieldProps}
  />;

export const withNumberReduxField = (WrappedComponent, name, props, fieldProps) =>
  <Field
    component={({ input, meta }) => <WrappedComponent {...{ ...input, meta }} {...props} />}
    name={name}
    normalize={value => toNumber(value.replace(/,/g, ''))}
    {...fieldProps}
  />;
