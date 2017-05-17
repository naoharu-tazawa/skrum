import React, { Component } from 'react';
import { connect } from 'react-redux';
import { companyPropTypes } from './propTypes';
import CompanyInfo from './CompanyInfo';

class CompanyInfoContainer extends Component {

  static propTypes = {
    company: companyPropTypes,
  };

  render() {
    const { company } = this.props;
    return !company ? null : (
      <CompanyInfo
        company={company}
        infoLink="./"
      />);
  }
}

const mapStateToProps = (state) => {
  const { basics = {} } = state;
  const { isFetching, company = {} } = basics;
  return isFetching ? {} : { company: company.company };
};

export default connect(
  mapStateToProps,
)(CompanyInfoContainer);
