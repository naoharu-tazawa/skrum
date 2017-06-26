import React from 'react';
import { connect } from 'react-redux';
import { reduxForm, Field } from 'redux-form';
import { isObject } from 'lodash';

export const withReduxForm = (WrappedForm, form) =>
  reduxForm({ form })(WrappedForm);

export const withLoadedReduxForm = (WrappedForm, form, mapStateToInitialValues) =>
  connect(
    state => ({ initialValues: mapStateToInitialValues(state) }),
  )(withReduxForm(WrappedForm, form));

export const withReduxField = (WrappedComponent, name) =>
  <Field
    component={({ input }) => <WrappedComponent {...input} />}
    name={name}
  />;

export const withItemisedReduxField = (WrappedComponent, name) =>
  <Field
    component={({ input }) => <WrappedComponent {...input} />}
    name={name}
    // format={item => (item || {}).name}
    normalize={(value, previousValue) => (isObject(value) ? value : previousValue)}
  />;
