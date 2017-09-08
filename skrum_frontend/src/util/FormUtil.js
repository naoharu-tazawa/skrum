import React from 'react';
import { connect } from 'react-redux';
import { reduxForm, Field } from 'redux-form';
import { isObject } from 'lodash';

export const withReduxForm = (WrappedForm, form, reduxFormProps = {}) =>
  reduxForm({ form, ...reduxFormProps })(WrappedForm);

export const withLoadedReduxForm = (WrappedForm, form, stateToInitialValues, reduxFormProps = {}) =>
  connect(
    state => ({ initialValues: stateToInitialValues ? stateToInitialValues(state) : {} }),
  )(withReduxForm(WrappedForm, form, reduxFormProps));

export const withReduxField = (WrappedComponent, name, fieldProps = {}) =>
  <Field
    component={({ input, meta }) => <WrappedComponent {...{...input, meta}} {...fieldProps} />}
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
