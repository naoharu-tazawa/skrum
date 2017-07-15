import React, { Component } from 'react';
import { connect } from 'react-redux';
import { AlignmentsInfoPropTypes } from './propTypes';
import OKRAlignmentsInfo from './OKRAlignmentsInfo';

class OKRAlignmentsInfoContainer extends Component {

  static propTypes = {
    alignments: AlignmentsInfoPropTypes,
  };

  render() {
    const { alignments = [] } = this.props;
    return <OKRAlignmentsInfo alignments={alignments} />;
  }
}

const mapBasicsStateToProps = subject => (state) => {
  const { [subject]: basics = {} } = state.basics || {};
  const { alignmentsInfo: alignments = [] } = basics || {};
  return { alignments };
};

export const UserOKRAlignmentsInfoContainer = connect(
  mapBasicsStateToProps('user'),
)(OKRAlignmentsInfoContainer);

export const GroupOKRAlignmentsInfoContainer = connect(
  mapBasicsStateToProps('group'),
)(OKRAlignmentsInfoContainer);
