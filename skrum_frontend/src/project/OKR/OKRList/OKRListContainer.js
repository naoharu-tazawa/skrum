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

const getOwnerTypeSubject = (ownerType) => {
  switch (ownerType) {
    case '1': return 'User';
    case '2': return 'Group';
    case '3': return 'Company';
    default: return '';
  }
};

const mapKeyResults = (kr) => {
  const { okrId, okrName, achievementRate, ownerType, status, ratioLockedFlg } = kr;
  const ownerSubject = `owner${getOwnerTypeSubject(ownerType)}`;
  const { [`${ownerSubject}Id`]: ownerId, [`${ownerSubject}Name`]: ownerName } = kr;
  return {
    id: okrId,
    name: okrName,
    achievementRate,
    owner: { id: ownerId, name: ownerName, type: ownerType },
    status,
    ratioLockedFlg,
  };
};

const mapStateToProps = subject => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const ownerSubject = `owner${_.upperFirst(subject)}`;
  const { okrs = [] } = basics || {};
  const items = okrs.map(
    ({ okrId, okrName, achievementRate,
       [`${ownerSubject}Id`]: ownerId,
       [`${ownerSubject}Name`]: ownerName,
       keyResults = [], status }) =>
    ({
      id: okrId,
      name: okrName,
      achievementRate,
      owner: { id: ownerId, name: ownerName },
      keyResults: keyResults.map(mapKeyResults),
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
