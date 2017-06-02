import React, { Component } from 'react';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import { mapOKR } from '../../../util/OKRUtil';
import OKRList from './OKRList';

class OKRListContainer extends Component {

  static propTypes = {
    okrs: okrsPropTypes,
  };

  render() {
    const { okrs = [] } = this.props;
    return <OKRList okrs={okrs} />;
  }
}

const mapBasicsStateToProps = (subject, ownerType) => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const { okrs = [] } = basics || {};
  return { okrs: okrs.map(okr => mapOKR({ ...okr, ownerType })) };
};

export const UserOKRListContainer = connect(
  mapBasicsStateToProps('user', '1'),
)(OKRListContainer);

export const GroupOKRListContainer = connect(
  mapBasicsStateToProps('group', '2'),
)(OKRListContainer);

export const CompanyOKRListContainer = connect(
  mapBasicsStateToProps('company', '3'),
)(OKRListContainer);
