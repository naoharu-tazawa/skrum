import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import InlineEditor, { inlineEditorPublicPropTypes } from '../../editors/InlineEditor';
import PotentialLeaders from './PotentialLeaders';

export default class InlinePotentialLeaders extends PureComponent {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    leaderUserId: PropTypes.number,
    leaderName: PropTypes.string,
    ...inlineEditorPublicPropTypes,
  };

  render() {
    const { groupId, leaderUserId, leaderName, onSubmit, ...inlineEditorProps } = this.props;
    const { leaderSelected } = this.state || {};
    return (
      <InlineEditor
        fluid
        dropdown
        {...{ value: leaderName, ...inlineEditorProps }}
        onSubmit={() => (leaderSelected ? onSubmit(leaderSelected) : Promise.resolve())}
      >
        {({ setRef, setValue, submit }) =>
          <PotentialLeaders
            ref={setRef}
            groupId={groupId}
            leaderUserId={leaderUserId}
            leaderName={leaderName}
            onChange={(leader) => {
              setValue(leader.name);
              this.setState({ leaderSelected: leader });
            }}
            onKeyPress={e => e.key === 'Enter' && submit()}
          />}
      </InlineEditor>
    );
  }
}
