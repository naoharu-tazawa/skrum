import React, { Component } from 'react';
import { connect } from 'react-redux';
import { okrsPropTypes } from './propTypes';
import OKRList from './OKRList';

class OKRListContainer extends Component {

  static propTypes = {
    items: okrsPropTypes.isRequired,
  };

  render() {
    return (
      <OKRList
        items={this.props.items}
      />);
  }
}

const mapStateToProps = (state) => {
  const { okrs = [] } = state.user.data || {};
  const items = okrs.map((okr) => {
    const { okrId, okrName, achievementRate, ownerUserId, ownerUserName, status } = okr;
    return {
      id: okrId,
      name: okrName,
      achievementRate,
      owner: { id: ownerUserId, name: ownerUserName },
      status,
    };
  });
  return { items };
};

export default connect(
  mapStateToProps,
)(OKRListContainer);
