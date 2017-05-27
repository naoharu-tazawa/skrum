import React, { Component } from 'react';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import { mapOKR } from '../../../util/OKRUtil';
import OKRList from './OKRList';

class OKRListContainer extends Component {

  static propTypes = {
    items: okrsPropTypes,
  };

  render() {
    const { items = [] } = this.props;
    return (
      <OKRList
        items={items}
      />);
  }
}

const mapBasicsStateToProps = (subject, ownerType) => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const { okrs = [] } = basics || {};
  const items = okrs.map(okr => mapOKR({ ...okr, ownerType }));
  return { items };
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
