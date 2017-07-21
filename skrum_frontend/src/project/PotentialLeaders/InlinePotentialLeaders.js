import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import InlineEditor, { inlineEditorPublicPropTypes } from '../../editors/InlineEditor';
import PotentialLeaders from './PotentialLeaders';

export default class InlinePotentialLeaders extends PureComponent {

  static propTypes = {
    groupId: PropTypes.number.isRequired,
    leaderUserId: PropTypes.number.isRequired,
    leaderName: PropTypes.string.isRequired,
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
        onSubmit={(_, completion) => leaderSelected && onSubmit(leaderSelected, completion)}
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
