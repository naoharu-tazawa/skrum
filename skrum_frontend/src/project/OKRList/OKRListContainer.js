import React, { Component } from 'react';
import { connect } from 'react-redux';
import _ from 'lodash';
import { okrsPropTypes } from './propTypes';
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

const mapStateToProps = subject => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const ownerSubject = `owner${_.upperFirst(subject)}`;
  const { okrs = [] } = basics || {};
  const items = okrs.map(
    ({ okrId, okrName, achievementRate,
       [`${ownerSubject}Id`]: ownerId,
       [`${ownerSubject}Name`]: ownerName,
       status }) =>
    ({
      id: okrId,
      name: okrName,
      achievementRate,
      owner: { id: ownerId, name: ownerName },
      status,
    }));
  return { items };
};

export const UserOKRListContainer = connect(
  mapStateToProps('user'),
)(OKRListContainer);

export const GroupOKRListContainer = connect(
  mapStateToProps('group'),
)(OKRListContainer);

export const CompanyOKRListContainer = connect(
  mapStateToProps('company'),
)(OKRListContainer);
