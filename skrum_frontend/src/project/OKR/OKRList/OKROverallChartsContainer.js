import React, { Component } from 'react';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import { mapOKR } from '../../../util/OKRUtil';
import OKROverallCharts from './OKROverallCharts';

class OKROverallChartsContainer extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
  };

  render() {
    const { okrs = [] } = this.props;
    return <OKROverallCharts okrs={okrs} />;
  }
}

const mapBasicsStateToProps = (subject, ownerType) => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const { okrs = [] } = basics || {};
  return { okrs: okrs.map(okr => mapOKR({ ...okr, ownerType })) };
};

export const UserOKROverallChartsContainer = connect(
  mapBasicsStateToProps('user', '1'),
)(OKROverallChartsContainer);

export const GroupOKROverallChartsContainer = connect(
  mapBasicsStateToProps('group', '2'),
)(OKROverallChartsContainer);

export const CompanyOKROverallChartsContainer = connect(
  mapBasicsStateToProps('company', '3'),
)(OKROverallChartsContainer);
